<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // View: mahasiswa hanya bisa melihat data diri sendiri
        DB::statement("
            CREATE OR REPLACE VIEW view_mahasiswa_profil AS
            SELECT
                m.id, m.nim, m.nama, m.email, m.program_studi, m.angkatan, m.user_id
            FROM mahasiswa m
        ");

        // View: nilai per mahasiswa (tanpa data sensitif dosen lain)
        DB::statement("
            CREATE OR REPLACE VIEW view_nilai_mahasiswa AS
            SELECT
                n.id,
                n.mahasiswa_id,
                m.nim,
                m.nama AS nama_mahasiswa,
                mk.kode AS kode_mk,
                mk.nama AS nama_mk,
                mk.sks,
                n.nilai,
                n.finalisasi,
                d.nama AS nama_dosen
            FROM nilai n
            JOIN mahasiswa m ON m.id = n.mahasiswa_id
            JOIN mata_kuliah mk ON mk.id = n.mata_kuliah_id
            JOIN dosen d ON d.id = n.dosen_id
        ");

        // View: tagihan per mahasiswa
        DB::statement("
            CREATE OR REPLACE VIEW view_keuangan_mahasiswa AS
            SELECT
                k.id,
                k.mahasiswa_id,
                m.nim,
                m.nama AS nama_mahasiswa,
                k.keterangan,
                k.nominal,
                k.status,
                k.created_at
            FROM keuangan k
            JOIN mahasiswa m ON m.id = k.mahasiswa_id
        ");

        // Stored procedure: input / update nilai dengan validasi finalisasi
        DB::statement("DROP PROCEDURE IF EXISTS sp_input_nilai");
        DB::statement("
            CREATE PROCEDURE sp_input_nilai(
                IN p_mahasiswa_id BIGINT UNSIGNED,
                IN p_mata_kuliah_id BIGINT UNSIGNED,
                IN p_dosen_id BIGINT UNSIGNED,
                IN p_nilai DECIMAL(5,2)
            )
            BEGIN
                DECLARE v_finalisasi TINYINT DEFAULT 0;
                DECLARE v_exists INT DEFAULT 0;

                SELECT COUNT(*), COALESCE(MAX(finalisasi), 0)
                INTO v_exists, v_finalisasi
                FROM nilai
                WHERE mahasiswa_id = p_mahasiswa_id
                  AND mata_kuliah_id = p_mata_kuliah_id;

                IF v_finalisasi = 1 THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Nilai sudah difinalisasi dan tidak dapat diubah.';
                END IF;

                IF v_exists > 0 THEN
                    UPDATE nilai
                    SET nilai = p_nilai, dosen_id = p_dosen_id, updated_at = NOW()
                    WHERE mahasiswa_id = p_mahasiswa_id
                      AND mata_kuliah_id = p_mata_kuliah_id;
                ELSE
                    INSERT INTO nilai (mahasiswa_id, mata_kuliah_id, dosen_id, nilai, finalisasi, created_at, updated_at)
                    VALUES (p_mahasiswa_id, p_mata_kuliah_id, p_dosen_id, p_nilai, 0, NOW(), NOW());
                END IF;
            END
        ");

        // Trigger: audit setiap perubahan nilai
        DB::statement("DROP TRIGGER IF EXISTS trg_audit_nilai_update");
        DB::statement("
            CREATE TRIGGER trg_audit_nilai_update
            AFTER UPDATE ON nilai
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, aktivitas, tabel, record_id, data_lama, data_baru, ip_address, created_at)
                VALUES (
                    NULL,
                    'UPDATE',
                    'nilai',
                    NEW.id,
                    JSON_OBJECT('nilai', OLD.nilai, 'finalisasi', OLD.finalisasi),
                    JSON_OBJECT('nilai', NEW.nilai, 'finalisasi', NEW.finalisasi),
                    NULL,
                    NOW()
                );
            END
        ");

        // Trigger: audit setiap perubahan status keuangan
        DB::statement("DROP TRIGGER IF EXISTS trg_audit_keuangan_update");
        DB::statement("
            CREATE TRIGGER trg_audit_keuangan_update
            AFTER UPDATE ON keuangan
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, aktivitas, tabel, record_id, data_lama, data_baru, ip_address, created_at)
                VALUES (
                    NULL,
                    'UPDATE',
                    'keuangan',
                    NEW.id,
                    JSON_OBJECT('status', OLD.status, 'nominal', OLD.nominal),
                    JSON_OBJECT('status', NEW.status, 'nominal', NEW.nominal),
                    NULL,
                    NOW()
                );
            END
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS trg_audit_keuangan_update");
        DB::statement("DROP TRIGGER IF EXISTS trg_audit_nilai_update");
        DB::statement("DROP PROCEDURE IF EXISTS sp_input_nilai");
        DB::statement("DROP VIEW IF EXISTS view_keuangan_mahasiswa");
        DB::statement("DROP VIEW IF EXISTS view_nilai_mahasiswa");
        DB::statement("DROP VIEW IF EXISTS view_mahasiswa_profil");
    }
};