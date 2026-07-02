<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HR Dummy Panel</title>
  <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      color-scheme: light;
      --bg: #0f172a;
      --bg-soft: #111c38;
      --panel: rgba(15, 23, 42, 0.72);
      --panel-strong: #0b1226;
      --card: rgba(255, 255, 255, 0.06);
      --card-border: rgba(255, 255, 255, 0.12);
      --text: #e5eefc;
      --muted: #9fb0d0;
      --primary: #31c48d;
      --primary-2: #59a7ff;
      --warning: #f59e0b;
      --danger: #fb7185;
      --success: #22c55e;
      --shadow: 0 24px 80px rgba(2, 6, 23, 0.45);
      --radius-xl: 28px;
      --radius-lg: 20px;
      --radius-md: 14px;
      --radius-sm: 10px;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Manrope', 'Segoe UI', sans-serif;
      background:
        radial-gradient(circle at top left, rgba(89, 167, 255, 0.22), transparent 34%),
        radial-gradient(circle at 85% 12%, rgba(49, 196, 141, 0.18), transparent 28%),
        linear-gradient(160deg, #06101f 0%, #0f172a 52%, #111827 100%);
      color: var(--text);
      min-height: 100vh;
    }

    a { color: inherit; }

    [v-cloak] { display: none; }

    .shell {
      width: min(1220px, calc(100% - 32px));
      margin: 0 auto;
      padding: 28px 0 48px;
    }

    .hero {
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04));
      border: 1px solid rgba(255,255,255,0.12);
      box-shadow: var(--shadow);
      border-radius: var(--radius-xl);
      padding: 28px;
      backdrop-filter: blur(18px);
    }

    .hero::before,
    .hero::after {
      content: '';
      position: absolute;
      inset: auto;
      border-radius: 999px;
      filter: blur(10px);
      pointer-events: none;
    }

    .hero::before {
      width: 220px;
      height: 220px;
      right: -60px;
      top: -70px;
      background: rgba(49, 196, 141, 0.18);
    }

    .hero::after {
      width: 180px;
      height: 180px;
      left: 56%;
      bottom: -80px;
      background: rgba(89, 167, 255, 0.18);
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 18px;
      margin-bottom: 28px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .brand-badge {
      width: 48px;
      height: 48px;
      border-radius: 16px;
      display: grid;
      place-items: center;
      font-weight: 800;
      background: linear-gradient(145deg, var(--primary), var(--primary-2));
      color: #fff;
      box-shadow: 0 12px 30px rgba(89, 167, 255, 0.25);
    }

    .brand h1 {
      font-size: 1.08rem;
      margin: 0;
    }

    .brand p {
      margin: 2px 0 0;
      color: var(--muted);
      font-size: 0.93rem;
    }

    .pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 14px;
      border-radius: 999px;
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.1);
      color: #dbeafe;
      font-size: 0.92rem;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1.4fr 0.9fr;
      gap: 20px;
      align-items: start;
    }

    .headline h2 {
      margin: 0;
      font-size: clamp(2rem, 4vw, 4rem);
      line-height: 1.02;
      letter-spacing: -0.04em;
      max-width: 11ch;
    }

    .headline p {
      margin: 16px 0 0;
      max-width: 58ch;
      color: var(--muted);
      line-height: 1.7;
    }

    .hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 22px;
    }

    .button {
      appearance: none;
      border: 0;
      border-radius: 14px;
      padding: 13px 18px;
      font-weight: 700;
      font-family: inherit;
      cursor: pointer;
      transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
    }

    .button:hover { transform: translateY(-1px); }

    .button-primary {
      background: linear-gradient(135deg, var(--primary), var(--primary-2));
      color: white;
      box-shadow: 0 14px 30px rgba(49, 196, 141, 0.25);
    }

    .button-secondary {
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.12);
      color: var(--text);
    }

    .hero-panel {
      background: rgba(3, 7, 18, 0.45);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 22px;
      padding: 18px;
    }

    .hero-panel h3 {
      margin: 0 0 10px;
      font-size: 1rem;
    }

    .hero-stat-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-top: 16px;
    }

    .hero-stat {
      padding: 16px;
      border-radius: 18px;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.08);
    }

    .hero-stat span {
      color: var(--muted);
      font-size: 0.86rem;
    }

    .hero-stat strong {
      display: block;
      margin-top: 10px;
      font-size: 1.8rem;
      letter-spacing: -0.04em;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 16px;
      margin: 20px 0;
    }

    .stat-card {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: var(--radius-lg);
      padding: 18px;
      box-shadow: var(--shadow);
      backdrop-filter: blur(14px);
    }

    .stat-card .label {
      color: var(--muted);
      font-size: 0.9rem;
    }

    .stat-card .value {
      margin-top: 10px;
      font-size: 2rem;
      font-weight: 800;
      letter-spacing: -0.04em;
    }

    .content-grid {
      display: grid;
      grid-template-columns: 1.05fr 0.95fr;
      gap: 18px;
      align-items: start;
    }

    .panel {
      background: rgba(7, 12, 24, 0.74);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: var(--radius-xl);
      padding: 20px;
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
    }

    .panel h3 {
      margin: 0 0 16px;
      font-size: 1.08rem;
    }

    .panel-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 16px;
    }

    .panel-subtitle {
      margin: 0;
      color: var(--muted);
      font-size: 0.92rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
    }

    .field {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .field.full { grid-column: 1 / -1; }

    label {
      font-size: 0.87rem;
      color: #d6def0;
    }

    input, select, textarea {
      width: 100%;
      border: 1px solid rgba(255,255,255,0.12);
      background: rgba(255,255,255,0.06);
      color: var(--text);
      border-radius: 14px;
      padding: 12px 14px;
      outline: none;
      font: inherit;
    }

    input::placeholder, textarea::placeholder {
      color: #7f8fab;
    }

    textarea { min-height: 96px; resize: vertical; }

    .table-wrap {
      overflow: auto;
      border-radius: 18px;
      border: 1px solid rgba(255,255,255,0.08);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 620px;
      background: rgba(255,255,255,0.03);
    }

    th, td {
      padding: 14px 16px;
      text-align: left;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      vertical-align: top;
    }

    th {
      color: #a9bddf;
      font-size: 0.84rem;
      letter-spacing: 0.03em;
      text-transform: uppercase;
    }

    td {
      color: #e9f2ff;
      font-size: 0.95rem;
    }

    .meta {
      color: var(--muted);
      font-size: 0.86rem;
      margin-top: 6px;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 11px;
      border-radius: 999px;
      font-size: 0.82rem;
      font-weight: 700;
      text-transform: capitalize;
    }

    .badge-aktif, .badge-disetujui {
      background: rgba(34, 197, 94, 0.14);
      color: #7bf5a9;
    }

    .badge-nonaktif, .badge-ditolak {
      background: rgba(251, 113, 133, 0.14);
      color: #ffb3c0;
    }

    .badge-pending {
      background: rgba(245, 158, 11, 0.14);
      color: #ffd68e;
    }

    .inline-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .chip {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 12px;
      border-radius: 999px;
      background: rgba(255,255,255,0.06);
      color: #dfe9ff;
      border: 1px solid rgba(255,255,255,0.08);
      font-size: 0.86rem;
    }

    .message {
      margin-top: 14px;
      padding: 12px 14px;
      border-radius: 14px;
      font-size: 0.92rem;
      border: 1px solid transparent;
    }

    .message.success {
      background: rgba(34, 197, 94, 0.12);
      color: #acffd0;
      border-color: rgba(34, 197, 94, 0.2);
    }

    .message.error {
      background: rgba(251, 113, 133, 0.12);
      color: #ffc5d1;
      border-color: rgba(251, 113, 133, 0.2);
    }

    .loading {
      opacity: 0.72;
      pointer-events: none;
    }

    .stack {
      display: grid;
      gap: 16px;
    }

    .empty {
      padding: 18px;
      border-radius: 16px;
      border: 1px dashed rgba(255,255,255,0.14);
      color: var(--muted);
      text-align: center;
    }

    @media (max-width: 1024px) {
      .hero-grid, .content-grid, .stats {
        grid-template-columns: 1fr;
      }

      .topbar {
        flex-direction: column;
        align-items: flex-start;
      }
    }

    @media (max-width: 720px) {
      .shell {
        width: min(100% - 20px, 1220px);
        padding: 18px 0 34px;
      }

      .hero, .panel, .stat-card {
        border-radius: 22px;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }

      .headline h2 {
        max-width: none;
      }
    }
  </style>
</head>
<body>
  <div id="app" v-cloak class="shell">
    <header class="hero">
      <div class="topbar">
        <div class="brand">
          <div class="brand-badge">HR</div>
          <div>
            <h1>HR Dummy Panel</h1>
            <p>Vue.js + PHP + SQLite untuk kebutuhan demo internal</p>
          </div>
        </div>
        <div class="pill">Database: SQLite lokal • API: PHP</div>
      </div>

      <div class="hero-grid">
        <div class="headline">
          <h2>Dashboard HR yang sederhana, rapi, dan langsung bisa dipakai untuk demo.</h2>
          <p>
            Website ini cocok untuk presentasi internal, simulasi alur HR, atau prototipe awal. Semua data karyawan dan cuti tersimpan di SQLite lokal dan otomatis terisi contoh data saat pertama dibuka.
          </p>

          <div class="hero-actions">
            <button class="button button-primary" @click="scrollToSection('employees')">Lihat Karyawan</button>
            <button class="button button-secondary" @click="scrollToSection('leaves')">Lihat Cuti</button>
            <button class="button button-secondary" @click="reloadAll" :disabled="loading" :class="{ loading: loading }">Refresh Data</button>
          </div>
        </div>

        <div class="hero-panel">
          <h3>Status Sistem</h3>
          <div class="hero-stat-grid">
            <div class="hero-stat">
              <span>Koneksi API</span>
              <strong>{{ apiHealth }}</strong>
            </div>
            <div class="hero-stat">
              <span>Database</span>
              <strong>Ready</strong>
            </div>
            <div class="hero-stat">
              <span>Karyawan Seed</span>
              <strong>4</strong>
            </div>
            <div class="hero-stat">
              <span>Menu Utama</span>
              <strong>2</strong>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section class="stats">
      <article class="stat-card">
        <div class="label">Total Karyawan</div>
        <div class="value">{{ summary.totalEmployees }}</div>
        <div class="meta">Semua data aktif dan nonaktif</div>
      </article>
      <article class="stat-card">
        <div class="label">Karyawan Aktif</div>
        <div class="value">{{ summary.activeEmployees }}</div>
        <div class="meta">Status HR saat ini</div>
      </article>
      <article class="stat-card">
        <div class="label">Cuti Pending</div>
        <div class="value">{{ summary.pendingLeaves }}</div>
        <div class="meta">Butuh approval</div>
      </article>
      <article class="stat-card">
        <div class="label">Cuti Disetujui</div>
        <div class="value">{{ summary.approvedLeaves }}</div>
        <div class="meta">Sudah diproses</div>
      </article>
    </section>

    <section class="content-grid" style="margin-bottom: 18px;">
      <div class="panel">
        <div class="panel-head">
          <div>
            <h3>Aktivitas Terbaru</h3>
            <p class="panel-subtitle">Log otomatis dari aksi data karyawan dan cuti.</p>
          </div>
        </div>

        <div class="stack" v-if="summary.recentActivities && summary.recentActivities.length">
          <div v-for="activity in summary.recentActivities" :key="activity.created_at + activity.detail" class="chip" style="width:100%; justify-content:space-between; align-items:flex-start; gap:14px; border-radius: 18px; padding: 14px 16px;">
            <div>
              <strong style="display:block; margin-bottom:4px;">{{ activity.actor }}</strong>
              <span style="color: var(--muted);">{{ activity.detail }}</span>
            </div>
            <span style="color: var(--muted); white-space: nowrap;">{{ activity.created_at }}</span>
          </div>
        </div>

        <div v-else class="empty">Belum ada aktivitas.</div>
      </div>

      <div class="panel">
        <div class="panel-head">
          <div>
            <h3>Status Data</h3>
            <p class="panel-subtitle">Ringkasan data yang dipakai untuk demo HR.</p>
          </div>
        </div>

        <div class="hero-stat-grid" style="margin-top: 0;">
          <div class="hero-stat">
            <span>Mode Demo</span>
            <strong>On</strong>
          </div>
          <div class="hero-stat">
            <span>Storage</span>
            <strong>SQLite</strong>
          </div>
          <div class="hero-stat">
            <span>API</span>
            <strong>PHP</strong>
          </div>
          <div class="hero-stat">
            <span>Frontend</span>
            <strong>Vue</strong>
          </div>
        </div>
      </div>
    </section>

    <section id="employees" class="content-grid">
      <div class="panel">
        <div class="panel-head">
          <div>
            <h3>Daftar Karyawan</h3>
            <p class="panel-subtitle">Tambah data baru atau ubah status langsung dari tabel.</p>
          </div>
          <span class="chip">{{ filteredEmployees.length }} data</span>
        </div>

        <div class="field" style="margin-bottom: 14px;">
          <label>Cari karyawan</label>
          <input v-model.trim="employeeQuery" type="search" placeholder="Nama, email, departemen, atau jabatan">
        </div>

        <div class="table-wrap" v-if="filteredEmployees.length">
          <table>
            <thead>
              <tr>
                <th>Nama</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="employee in filteredEmployees" :key="employee.id">
                <td>
                  <strong>{{ employee.full_name }}</strong>
                  <div class="meta">{{ employee.email }}</div>
                </td>
                <td>{{ employee.department }}</td>
                <td>{{ employee.position }}</td>
                <td>
                  <span class="badge" :class="'badge-' + employee.status">{{ employee.status }}</span>
                </td>
                <td>
                  <div class="inline-actions">
                    <button class="button button-secondary" @click="openEditEmployee(employee)">Edit</button>
                    <button class="button button-secondary" @click="setEmployeeStatus(employee.id, 'aktif')">Aktif</button>
                    <button class="button button-secondary" @click="setEmployeeStatus(employee.id, 'nonaktif')">Nonaktif</button>
                    <button class="button button-secondary" @click="deleteEmployee(employee.id)">Hapus</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="empty" v-else>Belum ada data karyawan yang cocok dengan pencarian.</div>
      </div>

      <div class="stack">
        <div class="panel">
          <h3>{{ employeeEditMode ? 'Edit Karyawan' : 'Tambah Karyawan' }}</h3>
          <form @submit.prevent="addEmployee">
            <div class="form-grid">
              <input v-model.number="employeeForm.id" type="hidden">
              <div class="field full">
                <label>Nama lengkap</label>
                <input v-model.trim="employeeForm.full_name" type="text" placeholder="Contoh: Dina Puspita">
              </div>
              <div class="field">
                <label>Email</label>
                <input v-model.trim="employeeForm.email" type="email" placeholder="dina@dummyhr.local">
              </div>
              <div class="field">
                <label>Departemen</label>
                <input v-model.trim="employeeForm.department" type="text" placeholder="Finance / HR / IT">
              </div>
              <div class="field">
                <label>Jabatan</label>
                <input v-model.trim="employeeForm.position" type="text" placeholder="Admin / Staff / Supervisor">
              </div>
              <div class="field">
                <label>Status</label>
                <select v-model="employeeForm.status">
                  <option value="aktif">aktif</option>
                  <option value="nonaktif">nonaktif</option>
                </select>
              </div>
            </div>
            <div class="hero-actions">
              <button class="button button-primary" type="submit" :disabled="loading">{{ employeeEditMode ? 'Simpan Perubahan' : 'Simpan Karyawan' }}</button>
              <button class="button button-secondary" type="button" @click="resetEmployeeForm">Reset</button>
            </div>
          </form>
        </div>

        <div class="panel">
          <h3>Ringkasan Departemen</h3>
          <div style="display:grid; gap: 10px;">
            <div v-for="item in departmentSummary" :key="item.department" class="chip" style="justify-content:space-between; width: 100%;">
              <span>{{ item.department }}</span>
              <strong>{{ item.total }}</strong>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="leaves" class="content-grid" style="margin-top: 18px;">
      <div class="panel">
        <div class="panel-head">
          <div>
            <h3>Permintaan Cuti</h3>
            <p class="panel-subtitle">Ajukan cuti baru untuk simulasi proses HR.</p>
          </div>
          <span class="chip">{{ leaves.length }} data</span>
        </div>

        <form @submit.prevent="addLeave" class="stack">
          <div class="form-grid">
            <div class="field full">
              <label>Karyawan</label>
              <select v-model.number="leaveForm.employee_id">
                <option :value="0" disabled>Pilih karyawan</option>
                <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                  {{ employee.full_name }} - {{ employee.department }}
                </option>
              </select>
            </div>
            <div class="field">
              <label>Tipe cuti</label>
              <select v-model="leaveForm.type">
                <option value="Tahunan">Tahunan</option>
                <option value="Sakit">Sakit</option>
                <option value="Izin">Izin</option>
                <option value="Khusus">Khusus</option>
              </select>
            </div>
            <div class="field">
              <label>Mulai</label>
              <input v-model="leaveForm.start_date" type="date">
            </div>
            <div class="field">
              <label>Sampai</label>
              <input v-model="leaveForm.end_date" type="date">
            </div>
            <div class="field full">
              <label>Alasan</label>
              <textarea v-model.trim="leaveForm.reason" placeholder="Tulis alasan cuti..." rows="4"></textarea>
            </div>
          </div>
          <div class="hero-actions">
            <button class="button button-primary" type="submit" :disabled="loading">Ajukan Cuti</button>
            <button class="button button-secondary" type="button" @click="resetLeaveForm">Reset</button>
          </div>
        </form>
      </div>

      <div class="panel">
        <div class="panel-head">
          <div>
            <h3>Daftar Cuti</h3>
            <p class="panel-subtitle">Approve atau reject permintaan cuti dari panel ini.</p>
          </div>
        </div>

        <div class="stack" v-if="leaves.length">
          <article v-for="leave in leaves" :key="leave.id" class="panel" style="padding:16px; background: rgba(255,255,255,0.04);">
            <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start;">
              <div>
                <strong>{{ leave.employee_name }}</strong>
                <div class="meta">{{ leave.type }} • {{ leave.start_date }} s/d {{ leave.end_date }}</div>
              </div>
              <span class="badge" :class="'badge-' + leave.status">{{ leave.status }}</span>
            </div>

            <p style="margin:12px 0 0; color:#dbe6fb; line-height:1.6;">{{ leave.reason }}</p>

            <div class="inline-actions" style="margin-top: 14px;">
              <button class="button button-primary" @click="setLeaveStatus(leave.id, 'disetujui')">Approve</button>
              <button class="button button-secondary" @click="setLeaveStatus(leave.id, 'ditolak')">Reject</button>
              <button class="button button-secondary" @click="deleteLeave(leave.id)">Hapus</button>
            </div>
          </article>
        </div>

        <div v-else class="empty">Belum ada permintaan cuti.</div>
      </div>
    </section>

    <div v-if="message.text" class="message" :class="message.type">
      {{ message.text }}
    </div>
  </div>

  <script>
    const { createApp, nextTick } = Vue;

    createApp({
      data() {
        return {
          loading: false,
          apiHealth: 'Online',
          employees: [],
          leaves: [],
          employeeQuery: '',
          employeeEditMode: false,
          summary: {
            totalEmployees: 0,
            activeEmployees: 0,
            pendingLeaves: 0,
            approvedLeaves: 0,
          },
          employeeForm: {
            full_name: '',
            email: '',
            department: '',
            position: '',
            status: 'aktif',
          },
          leaveForm: {
            employee_id: 0,
            type: 'Tahunan',
            start_date: '',
            end_date: '',
            reason: '',
          },
          message: {
            text: '',
            type: 'success',
          },
        };
      },
      computed: {
        filteredEmployees() {
          const query = this.employeeQuery.toLowerCase();

          if (!query) {
            return this.employees;
          }

          return this.employees.filter((employee) => {
            return [employee.full_name, employee.email, employee.department, employee.position, employee.status]
              .join(' ')
              .toLowerCase()
              .includes(query);
          });
        },
        departmentSummary() {
          const buckets = new Map();

          this.employees.forEach((employee) => {
            const total = buckets.get(employee.department) || 0;
            buckets.set(employee.department, total + 1);
          });

          return Array.from(buckets.entries())
            .map(([department, total]) => ({ department, total }))
            .sort((left, right) => right.total - left.total);
        },
      },
      mounted() {
        this.reloadAll();
      },
      methods: {
        async requestJson(path, options = {}) {
          const response = await fetch(path, {
            headers: {
              'Content-Type': 'application/json',
            },
            ...options,
          });

          const payload = await response.json();

          if (!response.ok || !payload.ok) {
            throw new Error(payload.message || 'Terjadi kesalahan pada server.');
          }

          return payload;
        },
        async reloadAll() {
          this.loading = true;

          try {
            const [summary, employees, leaves] = await Promise.all([
              this.requestJson('api/summary.php'),
              this.requestJson('api/employees.php'),
              this.requestJson('api/leaves.php'),
            ]);

            this.summary = summary.data;
            this.employees = employees.data;
            this.leaves = leaves.data;
            this.apiHealth = 'Online';
          } catch (error) {
            this.apiHealth = 'Error';
            this.showMessage(error.message, 'error');
          } finally {
            this.loading = false;
          }
        },
        async addEmployee() {
          const wasEditing = this.employeeEditMode;

          try {
            this.loading = true;
            await this.requestJson('api/employees.php', {
              method: 'POST',
              body: JSON.stringify(this.employeeForm),
            });
            this.resetEmployeeForm();
            await this.reloadAll();
            this.showMessage(wasEditing ? 'Data karyawan berhasil diperbarui.' : 'Karyawan baru berhasil ditambahkan.', 'success');
          } catch (error) {
            this.showMessage(error.message, 'error');
          } finally {
            this.loading = false;
          }
        },
        openEditEmployee(employee) {
          this.employeeEditMode = true;
          this.employeeForm = {
            id: employee.id,
            full_name: employee.full_name,
            email: employee.email,
            department: employee.department,
            position: employee.position,
            status: employee.status,
          };

          this.showMessage('Mode edit karyawan aktif.', 'success');
        },
        async setEmployeeStatus(id, status) {
          try {
            this.loading = true;
            await this.requestJson('api/employees.php', {
              method: 'POST',
              body: JSON.stringify({ id, status }),
            });
            await this.reloadAll();
            this.showMessage(`Status karyawan diperbarui ke ${status}.`, 'success');
          } catch (error) {
            this.showMessage(error.message, 'error');
          } finally {
            this.loading = false;
          }
        },
        async deleteEmployee(id) {
          if (!window.confirm('Hapus karyawan ini? Data cuti terkait juga akan ikut terhapus.')) {
            return;
          }

          try {
            this.loading = true;
            await this.requestJson('api/employees.php', {
              method: 'POST',
              body: JSON.stringify({ id, action: 'delete' }),
            });
            await this.reloadAll();
            this.showMessage('Karyawan berhasil dihapus.', 'success');
          } catch (error) {
            this.showMessage(error.message, 'error');
          } finally {
            this.loading = false;
          }
        },
        async addLeave() {
          try {
            this.loading = true;
            await this.requestJson('api/leaves.php', {
              method: 'POST',
              body: JSON.stringify(this.leaveForm),
            });
            this.resetLeaveForm();
            await this.reloadAll();
            this.showMessage('Permintaan cuti berhasil dikirim.', 'success');
          } catch (error) {
            this.showMessage(error.message, 'error');
          } finally {
            this.loading = false;
          }
        },
        async deleteLeave(id) {
          if (!window.confirm('Hapus data cuti ini?')) {
            return;
          }

          try {
            this.loading = true;
            await this.requestJson('api/leaves.php', {
              method: 'POST',
              body: JSON.stringify({ id, action: 'delete' }),
            });
            await this.reloadAll();
            this.showMessage('Data cuti berhasil dihapus.', 'success');
          } catch (error) {
            this.showMessage(error.message, 'error');
          } finally {
            this.loading = false;
          }
        },
        async setLeaveStatus(id, status) {
          try {
            this.loading = true;
            await this.requestJson('api/leaves.php', {
              method: 'POST',
              body: JSON.stringify({ id, status }),
            });
            await this.reloadAll();
            this.showMessage(`Status cuti diperbarui ke ${status}.`, 'success');
          } catch (error) {
            this.showMessage(error.message, 'error');
          } finally {
            this.loading = false;
          }
        },
        resetEmployeeForm() {
          this.employeeEditMode = false;
          this.employeeForm = {
            id: 0,
            full_name: '',
            email: '',
            department: '',
            position: '',
            status: 'aktif',
          };
        },
        resetLeaveForm() {
          this.leaveForm = {
            employee_id: this.employees[0] ? this.employees[0].id : 0,
            type: 'Tahunan',
            start_date: '',
            end_date: '',
            reason: '',
          };
        },
        showMessage(text, type) {
          this.message = { text, type };

          window.clearTimeout(this.messageTimer);
          this.messageTimer = window.setTimeout(() => {
            this.message.text = '';
          }, 3200);
        },
        async scrollToSection(id) {
          await nextTick();
          document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },
      },
      watch: {
        employees(newEmployees) {
          if (!this.leaveForm.employee_id && newEmployees.length) {
            this.leaveForm.employee_id = newEmployees[0].id;
          }
        },
      },
    }).mount('#app');
  </script>
</body>
</html>