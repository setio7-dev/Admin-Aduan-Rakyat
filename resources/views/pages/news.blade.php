@extends("components.dashboard")
@section("dashboard")

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="card-title">Berita</h4>
            <button class="btn btn-secondary text-white btn-sm" onclick="openAddModal()">Tambah Berita</button>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Gambar</th>
                        <th>Deskripsi</th>
                        <th>Detail</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="newsBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add News -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="addNewsForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Detail</label>
                        <textarea name="text" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Gambar</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit News -->
<div class="modal fade" id="editNewsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editNewsForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editId">

                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" id="editTitle" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" id="editDescription" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Detail</label>
                        <textarea name="text" id="editText" class="form-control"></textarea>
                    </div>

                    <div class="mb-2">
                        <label>Gambar Saat Ini</label><br>
                        <img id="editImagePreview" style="width:100px;height:100px;border-radius:6px;object-fit:cover;">
                    </div>

                    <div class="mb-3">
                        <label>Gambar Baru (Opsional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
const newsBody = document.getElementById("newsBody");

const fetchNews = async () => {
    newsBody.innerHTML = "";
    const token = localStorage.getItem("token");
    const response = await axios.get("/api/news", { headers: { Authorization: `Bearer ${token}` } });
    const data = response.data.data;

    data.forEach((item, index) => {
        newsBody.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td style="text-align: justify;">${item.title.slice(0, 30)}...</td>
                <td><img src="/storage/${item.image}" style="width:70px;height:70px;object-fit:cover;border-radius:6px;"></td>
                <td style="text-align: justify;">${item.description.slice(0, 30)}...</td>
                <td style="text-align: justify;">${item.text.slice(0, 30)}...</td>
                <td>
                    <button class="btn btn-secondary btn-sm text-white" onclick="editNews(${item.id})">Edit</button>
                    <button class="btn btn-danger text-white btn-sm" onclick="deleteNews(${item.id})">Delete</button>
                </td>
            </tr>
        `;
    });
};

const openAddModal = () => {
    new bootstrap.Modal(document.getElementById('addNewsModal')).show();
};

document.getElementById("addNewsForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const token = localStorage.getItem("token");
    const form = new FormData(e.target);

    await axios.post("/api/news", form, {
        headers: { Authorization: `Bearer ${token}`, "Content-Type": "multipart/form-data" }
    });

    Swal.fire("Berhasil", "Berita berhasil ditambahkan", "success");
    bootstrap.Modal.getInstance(document.getElementById('addNewsModal')).hide();
    fetchNews();
});

const editNews = async (id) => {
    const token = localStorage.getItem("token");
    const res = await axios.get(`/api/news/${id}`, { headers: { Authorization: `Bearer ${token}` }});
    const item = res.data.data;

    document.getElementById("editId").value = item.id;
    document.getElementById("editTitle").value = item.title;
    document.getElementById("editDescription").value = item.description;
    document.getElementById("editText").value = item.text;
    document.getElementById("editImagePreview").src = `/storage/${item.image}`;

    new bootstrap.Modal(document.getElementById('editNewsModal')).show();
};

document.getElementById("editNewsForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const token = localStorage.getItem("token");
    const form = new FormData(e.target);
    const id = document.getElementById("editId").value;

    form.append("_method", "PUT");

    await axios.post(`/api/news/${id}`, form, {
        headers: { Authorization: `Bearer ${token}`, "Content-Type": "multipart/form-data" }
    });

    Swal.fire("Berhasil", "Berita berhasil diupdate", "success");
    bootstrap.Modal.getInstance(document.getElementById('editNewsModal')).hide();
    fetchNews();
});

const deleteNews = async (id) => {
    const token = localStorage.getItem("token");
    const confirm = await Swal.fire({ title: "Hapus berita ini?", icon: "warning", showCancelButton: true });
    if (!confirm.isConfirmed) return;

    await axios.delete(`/api/news/${id}`, { headers: { Authorization: `Bearer ${token}` } });
    Swal.fire("Terhapus", "", "success");
    fetchNews();
};

fetchNews();
</script>

@endsection
