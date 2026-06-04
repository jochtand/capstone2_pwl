process.env.TZ = 'Asia/Jakarta';
const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const app = express();
const port = 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Konfigurasi Koneksi Database MySQL
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',      
    password: '',      
    database: 'capstone2_db',
    timezone:  '+07:00',
    dateStrings: true
});

// Cek Koneksi Database
db.connect((err) => {
    if (err) {
        console.error('Gagal koneksi ke database:', err);
        return;
    }
    console.log('Berhasil terkoneksi ke database MySQL capstone2_db!');
});

// ==========================================
// Endpoint API untuk Ruangan dan Users (Administrator)
// ==========================================

// 1. Get Semua Data Ruangan
app.get('/api/ruangan', (req, res) => {
    const query = 'SELECT * FROM ruangan';
    db.query(query, (err, results) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json({
            status: 'success',
            data: results
        });
    });
});

// 1a. Tambah Ruangan Baru
app.post('/api/ruangan', (req, res) => {
    const { nama_ruangan } = req.body;
    db.query('INSERT INTO ruangan (nama_ruangan) VALUES (?)', [nama_ruangan], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', message: 'Ruangan berhasil ditambahkan' });
    });
});

// 1b. Hapus Ruangan
app.delete('/api/ruangan/:id', (req, res) => {
    const roomId = req.params.id;
    // Catatan: Pastikan menghapus ruangan tidak bentrok dengan data inventaris yang sudah ada di ruangan tersebut
    db.query('DELETE FROM ruangan WHERE id = ?', [roomId], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', message: 'Ruangan berhasil dihapus' });
    });
});

// 1c. Update Ruangan
app.put('/api/ruangan/:id', (req, res) => {
    const { nama_ruangan } = req.body;
    db.query('UPDATE ruangan SET nama_ruangan = ? WHERE id = ?', [nama_ruangan, req.params.id], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', message: 'Ruangan berhasil diupdate' });
    });
});

// 2. Get Semua Data Users (Administrator)
app.get('/api/users', (req, res) => {
    const query = 'SELECT id, nama, email, role FROM users';
    db.query(query, (err, results) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json({
            status: 'success',
            data: results
        });
    });
});

// 2a. Tambah Pengguna Baru
app.post('/api/users', (req, res) => {
    const { nama, email, password, role } = req.body;
    const query = 'INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)';
    db.query(query, [nama, email, password, role], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', message: 'Pengguna berhasil ditambahkan' });
    });
});

// 2b. Hapus Pengguna
app.delete('/api/users/:id', (req, res) => {
    const userId = req.params.id;
    db.query('DELETE FROM users WHERE id = ?', [userId], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', message: 'Pengguna berhasil dihapus' });
    });
});

// 2c. Update Pengguna
app.put('/api/users/:id', (req, res) => {
    const { nama, email, role } = req.body;
    const query = 'UPDATE users SET nama = ?, email = ?, role = ? WHERE id = ?';
    db.query(query, [nama, email, role, req.params.id], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', message: 'Pengguna berhasil diupdate' });
    });
});

// ==========================================
// ENDPOINT MASTER DATA (INVENTARIS & BHP)
// ==========================================

// 3. Get Semua Data Inventaris beserta Nama Ruangannya
app.get('/api/inventaris', (req, res) => {
    const query = `
        SELECT i.*, r.nama_ruangan 
        FROM inventaris i 
        JOIN ruangan r ON i.ruangan_id = r.id
    `;
    db.query(query, (err, results) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json({
            status: 'success',
            data: results
        });
    });
});

// 4. Get Semua Data BHP beserta Nama Ruangannya
app.get('/api/bhp', (req, res) => {
    const query = `
        SELECT b.*, r.nama_ruangan 
        FROM bhp b 
        JOIN ruangan r ON b.ruangan_id = r.id
    `;
    db.query(query, (err, results) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json({
            status: 'success',
            data: results
        });
    });
});

// 5. Get Semua Data Draf Pengadaan (Untuk Kepala Lab / Kaprodi)
app.get('/api/draft-pengadaan', (req, res) => {
    const query = `
        SELECT dp.*, u.nama AS nama_kepala_lab,
               (SELECT COUNT(*) FROM detail_pengadaan WHERE draft_id = dp.id) AS jumlah_barang
        FROM draft_pengadaan dp
        JOIN users u ON dp.kepala_lab_id = u.id
        ORDER BY dp.created_at DESC
    `;
    db.query(query, (err, results) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        res.json({
            status: 'success',
            data: results
        });
    });
});

// ==========================================
// ENDPOINT AUTENTIKASI
// ==========================================

// 6. Login User
app.post('/api/login', (req, res) => {
    const { email, password } = req.body;
    const query = 'SELECT id, nama, email, role FROM users WHERE email = ? AND password = ?';
    
    db.query(query, [email, password], (err, results) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }

        // Jika user ditemukan
        if (results.length > 0) {
            res.json({
                status: 'success',
                message: 'Login berhasil',
                data: results[0] 
            });
        } else {
            res.status(401).json({
                status: 'error',
                message: 'Email atau password salah!'
            });
        }
    });
});

// 7. Buat Draf Pengadaan Baru & Detail Barangnya (Transaksi One-to-Many)
app.post('/api/draft-pengadaan', (req, res) => {
    const { kepala_lab_id, tahun, tgl_pengajuan, items } = req.body;

    // Memulai transaksi database
    db.beginTransaction((err) => {
        if (err) return res.status(500).json({ error: err.message });

        // Step 1: Simpan header draf-nya dulu
        const queryDraft = 'INSERT INTO draft_pengadaan (kepala_lab_id, tahun, tgl_pengajuan, status) VALUES (?, ?, ?, "Draft")';
        
        db.query(queryDraft, [kepala_lab_id, tahun, tgl_pengajuan], (err, resultDraft) => {
            if (err) {
                return db.rollback(() => res.status(500).json({ error: err.message }));
            }

            const draftId = resultDraft.insertId;

            // Step 2: Cek apakah ada barang yang diinput
            if (!items || items.length === 0) {
                return db.commit((err) => {
                    if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                    res.json({ status: 'success', message: 'Draf kosong berhasil dibuat' });
                });
            }

            // Step 3: Siapkan data detail barang untuk di-insert sekaligus (Bulk Insert)
            const queryDetail = 'INSERT INTO detail_pengadaan (draft_id, nama_barang, harga, jumlah, link_pembelian, inventaris_diganti_id) VALUES ?';
            const detailValues = items.map(item => [
                draftId,
                item.nama_barang,
                item.harga,
                item.jumlah,
                item.link_pembelian || null,
                item.inventaris_diganti_id || null 
            ]);

            db.query(queryDetail, [detailValues], (err, resultDetail) => {
                if (err) {
                    return db.rollback(() => res.status(500).json({ error: err.message }));
                }

                // Step 4: Jika semua sukses, kunci transaksinya (Commit)
                db.commit((err) => {
                    if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                    res.json({ 
                        status: 'success', 
                        message: 'Draf dan detail barang berhasil diajukan!' 
                    });
                });
            });
        });
    });
});

// 8. Ambil Detail Draf Berdasarkan ID
app.get('/api/draft-pengadaan/:id', (req, res) => {
    const draftId = req.params.id;

    // Query 1: Ambil Header Draf (beserta nama pembuat)
    const queryHeader = `
        SELECT d.*, u.nama AS nama_kepala_lab 
        FROM draft_pengadaan d 
        JOIN users u ON d.kepala_lab_id = u.id 
        WHERE d.id = ?`;

    // Query 2: Ambil Detail Barang
    const queryDetail = `SELECT * FROM detail_pengadaan WHERE draft_id = ?`;

    // Eksekusi Query 1
    db.query(queryHeader, [draftId], (err, headerResults) => {
        if (err) return res.status(500).json({ error: err.message });
        if (headerResults.length === 0) return res.status(404).json({ message: 'Draf tidak ditemukan' });

        const draftData = headerResults[0];

        // Eksekusi Query 2
        db.query(queryDetail, [draftId], (err, detailResults) => {
            if (err) return res.status(500).json({ error: err.message });
            
            // Gabungkan data barang ke dalam draftData
            draftData.items = detailResults;
            
            res.json({
                status: 'success',
                data: draftData
            });
        });
    });
});

// 9. Kirim Draf ke Kaprodi (Ubah Status ke Locked)
app.put('/api/draft-pengadaan/:id/submit', (req, res) => {
    const draftId = req.params.id;
    
    // Hanya ubah jika statusnya masih 'Draft'
    const query = 'UPDATE draft_pengadaan SET status = "Locked" WHERE id = ? AND status = "Draft"';
    
    db.query(query, [draftId], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        
        if (result.affectedRows === 0) {
            return res.status(400).json({ status: 'error', message: 'Draf tidak ditemukan atau sudah dikunci.' });
        }
        
        res.json({ status: 'success', message: 'Draf berhasil dikirim ke Kaprodi!' });
    });
});

// ==========================================
// ENDPOINT REVISI - EDIT & DELETE ITEM DRAF
// ==========================================

// Update Item Draf (Barang)
app.put('/api/detail-pengadaan/:id', (req, res) => {
    const { nama_barang, harga, jumlah, link_pembelian } = req.body;
    const query = 'UPDATE detail_pengadaan SET nama_barang=?, harga=?, jumlah=?, link_pembelian=? WHERE id=?';
    db.query(query, [nama_barang, harga, jumlah, link_pembelian, req.params.id], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', message: 'Barang berhasil diupdate' });
    });
});

// Delete Item Draf (Barang)
app.delete('/api/detail-pengadaan/:id', (req, res) => {
    db.query('DELETE FROM detail_pengadaan WHERE id=?', [req.params.id], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', message: 'Barang berhasil dihapus' });
    });
});

// Hapus Draf Pengadaan (Hanya bisa jika masih kosong tanpa barang)
app.delete('/api/draft-pengadaan/:id', (req, res) => {
    const draftId = req.params.id;

    // Cek dulu apakah di dalam draf ini masih ada barang?
    db.query('SELECT COUNT(*) as jml FROM detail_pengadaan WHERE draft_id = ?', [draftId], (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        
        // Jika masih ada barang, TOLAK proses hapus!
        if (results[0].jml > 0) {
            return res.status(400).json({ error: 'GAGAL: Kosongkan dulu semua barang di dalam draf ini jika ingin menghapusnya!' });
        }

        // Jika benar-benar kosong (0 barang), baru boleh dihapus drafnya
        db.query('DELETE FROM draft_pengadaan WHERE id = ? AND status = "Draft"', [draftId], (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            if (result.affectedRows === 0) return res.status(400).json({ error: 'Draf tidak ditemukan atau sudah dikunci.' });
            
            res.json({ status: 'success', message: 'Draf kosong berhasil dihapus!' });
        });
    });
});

// ==========================================
// ENDPOINT KAPRODI
// ==========================================

// 10. Review Per-Barang & Finalisasi Draf oleh Kaprodi
app.put('/api/kaprodi/draft/:id/finalize', (req, res) => {
    const draftId = req.params.id;
    const { items } = req.body; // Array objek: [{ id: 1, status: 'Disetujui' }, { id: 2, status: 'Ditolak' }]

    if (!items || items.length === 0) {
        return res.status(400).json({ error: 'Data review barang tidak boleh kosong.' });
    }

    db.beginTransaction((err) => {
        if (err) return res.status(500).json({ error: err.message });

        // Step 1: Update status masing-masing barang (Looping update)
        let completedUpdates = 0;
        let hasError = false;

        items.forEach((item) => {
            const queryDetail = 'UPDATE detail_pengadaan SET status = ? WHERE id = ? AND draft_id = ?';
            db.query(queryDetail, [item.status, item.id, draftId], (err, result) => {
                if (err && !hasError) {
                    hasError = true;
                    return db.rollback(() => res.status(500).json({ error: err.message }));
                }
                
                completedUpdates++;
                
                // Jika semua barang sudah di-update, lanjut ke Step 2
                if (completedUpdates === items.length && !hasError) {
                    
                    // Step 2: Kunci draf utama menjadi 'Finalized'
                    const queryDraft = 'UPDATE draft_pengadaan SET status = "Finalized" WHERE id = ? AND status = "Locked"';
                    db.query(queryDraft, [draftId], (err, result) => {
                        if (err) {
                            return db.rollback(() => res.status(500).json({ error: err.message }));
                        }

                        db.commit((err) => {
                            if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                            res.json({ 
                                status: 'success', 
                                message: 'Review barang berhasil disimpan dan draf telah di-Finalisasi!' 
                            });
                        });
                    });
                }
            });
        });
    });
});

// ==========================================
// ENDPOINT STAF ADMINISTRASI
// ==========================================

// 11. Terima Barang (Update Tanggal, Generate Label, & Masuk Ruangan)
app.put('/api/staf-admin/barang/:id/terima', (req, res) => {
    const detailId = req.params.id;
    // Data baru yang dikirim dari form Staf Admin
    const { tanggal_terima, ruangan_id, kondisi, kategori, nama_barang, jumlah } = req.body;

    // Logika Generate Label Otomatis
    // Contoh Hasil: INV-2026-0005 atau BHP-2026-0005
    const tahun = new Date(tanggal_terima).getFullYear() || new Date().getFullYear();
    const prefix = kategori === 'Inventaris' ? 'INV' : 'BHP';
    const label = `${prefix}-${tahun}-${detailId.toString().padStart(4, '0')}`;

    // Mulai Transaksi Database (Agar aman)
    db.beginTransaction((err) => {
        if (err) return res.status(500).json({ error: err.message });

        // Step 1: Update data barang di draf pengadaan (tandai sudah diterima & beri label)
        const queryUpdate = 'UPDATE detail_pengadaan SET tanggal_terima = ?, label = ? WHERE id = ?';
        db.query(queryUpdate, [tanggal_terima, label, detailId], (err, result) => {
            if (err) return db.rollback(() => res.status(500).json({ error: err.message }));

            // Step 2: Masukkan fisik barang ke Ruangan (Tabel Inventaris / BHP)
            if (kategori === 'Inventaris') {
                const queryInv = 'INSERT INTO inventaris (nama_barang, ruangan_id, kondisi) VALUES (?, ?, ?)';
                db.query(queryInv, [nama_barang, ruangan_id, kondisi], (err) => {
                    if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                    
                    db.commit((err) => {
                        if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                        res.json({ status: 'success', message: 'Barang Inventaris diterima & dialokasikan ke ruangan!', label: label });
                    });
                });
            } else {
                // Jika BHP, jumlah stoknya dimasukkan
                const queryBhp = 'INSERT INTO bhp (nama_barang, ruangan_id, stok) VALUES (?, ?, ?)';
                db.query(queryBhp, [nama_barang, ruangan_id, jumlah], (err) => {
                    if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                    
                    db.commit((err) => {
                        if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                        res.json({ status: 'success', message: 'BHP diterima & dialokasikan ke ruangan!', label: label });
                    });
                });
            }
        });
    });
});

// ==========================================
// ENDPOINT STAF LABORATORIUM
// ==========================================

// 12. Simpan Log Maintenance & Potong Stok BHP
app.post('/api/staf-lab/maintenance', (req, res) => {
    const { inventaris_id, tanggal_maintenance, deskripsi, kondisi_sesudah, bhp_id, jumlah_bhp } = req.body;

    // Memulai Transaksi 3 Tahap
    db.beginTransaction((err) => {
        if (err) return res.status(500).json({ error: err.message });

        // Step 1: Insert Log Maintenance
        const queryLog = 'INSERT INTO maintenance_log (inventaris_id, tanggal_maintenance, deskripsi, kondisi_sesudah, bhp_id, jumlah_bhp) VALUES (?, ?, ?, ?, ?, ?)';
        db.query(queryLog, [inventaris_id, tanggal_maintenance, deskripsi, kondisi_sesudah, bhp_id || null, jumlah_bhp || 0], (err, resultLog) => {
            if (err) return db.rollback(() => res.status(500).json({ error: err.message }));

            // Step 2: Update Kondisi Inventaris
            const queryInv = 'UPDATE inventaris SET kondisi = ? WHERE id = ?';
            db.query(queryInv, [kondisi_sesudah, inventaris_id], (err, resultInv) => {
                if (err) return db.rollback(() => res.status(500).json({ error: err.message }));

                // Step 3: Potong Stok BHP (Jika staf memilih BHP yang digunakan)
                if (bhp_id && jumlah_bhp > 0) {
                    const queryBhp = 'UPDATE bhp SET stok = stok - ? WHERE id = ?';
                    db.query(queryBhp, [jumlah_bhp, bhp_id], (err, resultBhp) => {
                        if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                        
                        // Sukses 3 Tahap -> Kunci Data (Commit)
                        db.commit((err) => {
                            if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                            res.json({ status: 'success', message: 'Maintenance berhasil dicatat dan stok BHP terpotong otomatis!' });
                        });
                    });
                } else {
                    // Jika tidak pakai BHP, langsung kunci data (Commit)
                    db.commit((err) => {
                        if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                        res.json({ status: 'success', message: 'Maintenance berhasil dicatat tanpa penggunaan BHP.' });
                    });
                }
            });
        });
    });
});

// 13. Ambil Data Log Maintenance
app.get('/api/maintenance-log', (req, res) => {
    const query = `
        SELECT m.*, i.nama_barang AS nama_inventaris, b.nama_barang AS nama_bhp 
        FROM maintenance_log m
        JOIN inventaris i ON m.inventaris_id = i.id
        LEFT JOIN bhp b ON m.bhp_id = b.id
        ORDER BY m.created_at DESC
    `;
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ status: 'success', data: results });
    });
});

// ==========================================
// ENDPOINT STAF LABORATORIUM (MANAJEMEN ASET)
// ==========================================

// 14. Tambah Inventaris & BHP Manual
app.post('/api/staf-lab/inventaris', (req, res) => {
    db.query('INSERT INTO inventaris (nama_barang, ruangan_id, kondisi) VALUES (?, ?, ?)', [req.body.nama_barang, req.body.ruangan_id, req.body.kondisi], (err) => {
        if(err) return res.status(500).json({error: err.message});
        res.json({status: 'success', message: 'Aset berhasil ditambahkan!'});
    });
});
app.post('/api/staf-lab/bhp', (req, res) => {
    db.query('INSERT INTO bhp (nama_barang, ruangan_id, stok) VALUES (?, ?, ?)', [req.body.nama_barang, req.body.ruangan_id, req.body.stok], (err) => {
        if(err) return res.status(500).json({error: err.message});
        res.json({status: 'success', message: 'BHP berhasil ditambahkan!'});
    });
});

// 15. Edit Inventaris & BHP (No delete - Sesuai Standar Audit)
app.put('/api/staf-lab/inventaris/:id', (req, res) => {
    db.query('UPDATE inventaris SET nama_barang=?, ruangan_id=?, kondisi=? WHERE id=?', [req.body.nama_barang, req.body.ruangan_id, req.body.kondisi, req.params.id], (err) => {
        if(err) return res.status(500).json({error: err.message});
        res.json({status: 'success', message: 'Aset berhasil diupdate!'});
    });
});
app.put('/api/staf-lab/bhp/:id', (req, res) => {
    db.query('UPDATE bhp SET nama_barang=?, ruangan_id=?, stok=? WHERE id=?', [req.body.nama_barang, req.body.ruangan_id, req.body.stok, req.params.id], (err) => {
        if(err) return res.status(500).json({error: err.message});
        res.json({status: 'success', message: 'Stok BHP berhasil diupdate!'});
    });
});

// 16. Replace Barang Rusak (Logika Cerdas)
app.put('/api/staf-lab/replace-inventaris/:idLama', (req, res) => {
    const idLama = req.params.idLama;
    const { idBaru } = req.body; 

    db.beginTransaction((err) => {
        if(err) return res.status(500).json({error: err.message});

        // Step 1: Cari tau barang rusak ini ada di ruangan mana?
        db.query('SELECT ruangan_id FROM inventaris WHERE id = ?', [idLama], (err, results) => {
            if(err || results.length === 0) return db.rollback(() => res.status(500).json({error: "Gagal melacak ruangan barang lama."}));
            const ruanganTarget = results[0].ruangan_id;

            // Step 2: Buang barang lama (Status = Afkir)
            db.query('UPDATE inventaris SET kondisi = "Afkir" WHERE id = ?', [idLama], (err) => {
                if(err) return db.rollback(() => res.status(500).json({error: err.message}));

                // Step 3: Pindahkan barang baru ke ruangan tersebut
                db.query('UPDATE inventaris SET ruangan_id = ? WHERE id = ?', [ruanganTarget, idBaru], (err) => {
                    if(err) return db.rollback(() => res.status(500).json({error: err.message}));

                    db.commit((err) => {
                        if(err) return db.rollback(() => res.status(500).json({error: err.message}));
                        res.json({status: 'success', message: 'Luar Biasa! Barang rusak berhasil direplace dengan yang baru!'});
                    });
                });
            });
        });
    });
});

// ==========================================
// Server Listening
// ==========================================
app.listen(port, () => {
    console.log(`Server Backend berjalan di http://localhost:${port}`);
});