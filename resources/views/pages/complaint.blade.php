@extends("components.dashboard")
@section("dashboard")

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="card-title">Pengaduan</h4>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Lokasi</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Tanggal Ditindak</th>
                        <th>Informasi</th>
                        <th>Hasil</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="complaintBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editComplaintModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editComplaintForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editId">

                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" id="editTitle" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Kategori</label>
                        <input type="text" name="category" id="editCategory" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" id="editDescription" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="location" id="editLocation" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Bukti Saat Ini</label><br>
                        <img id="editProofPreview" style="width:100px;height:100px;border-radius:6px;object-fit:cover;">
                    </div>

                    <div class="mb-3">
                        <label>Bukti Baru (Opsional)</label>
                        <input type="file" name="proof" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Ditindak</label>
                        <input type="date" name="date_followed_up" id="editDateFollowed" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Informasi</label>
                        <textarea name="information" id="editInformation" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Hasil</label>
                        <textarea name="result" id="editResult" class="form-control"></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" onclick="submitStatus('accepted')" class="btn text-white btn-success w-50">Disetujui</button>
                        <button type="button" onclick="submitStatus('rejected')" class="btn text-white btn-danger w-50">Ditolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
const complaintBody = document.getElementById("complaintBody")

const fetchComplaints = async () => {
    complaintBody.innerHTML = ""
    const token = localStorage.getItem("token")
    const response = await axios.get("/api/complaints", { headers: { Authorization: `Bearer ${token}` } })
    const data = response.data.data

    data.forEach((item, index) => {
        let statusLabel = ""
        if (item.status === "accepted") statusLabel = '<span class="badge bg-success">Disetujui</span>'
        else if (item.status === "rejected") statusLabel = '<span class="badge bg-danger">Ditolak</span>'
        else statusLabel = '<span class="badge bg-warning">Diproses</span>'

        complaintBody.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.title}</td>
                <td>${item.category}</td>
                <td style="text-align: justify;">${item.description}</td>
                <td>${item.location}</td>
                <td><img src="/storage/${item.proof}" style="width:70px;height:70px;object-fit:cover;border-radius:6px;"></td>
                <td>${statusLabel}</td>
                <td>${item.date_followed_up ?? "-"}</td>
                <td style="text-align: justify;">${item.information ?? "-"}</td>
                <td style="text-align: justify;">${item.result ?? "-"}</td>
                <td>
                    <button class="btn btn-secondary btn-sm text-white" onclick="editComplaint(${item.id})">Edit</button>
                    <button class="btn btn-danger btn-sm text-white" onclick="deleteComplaint(${item.id})">Delete</button>
                </td>
            </tr>
        `
    })
}

const editComplaint = async (id) => {
    const token = localStorage.getItem("token")
    const res = await axios.get(`/api/complaints/${id}`, { headers: { Authorization: `Bearer ${token}` } })
    const item = res.data.data

    document.getElementById("editId").value = item.id
    document.getElementById("editTitle").value = item.title
    document.getElementById("editCategory").value = item.category
    document.getElementById("editDescription").value = item.description
    document.getElementById("editLocation").value = item.location
    document.getElementById("editProofPreview").src = `/storage/${item.proof}`
    document.getElementById("editDateFollowed").value = item.date_followed_up ?? ""
    document.getElementById("editInformation").value = item.information ?? ""
    document.getElementById("editResult").value = item.result ?? ""

    new bootstrap.Modal(document.getElementById('editComplaintModal')).show()
}

function submitStatus(status) {
    const form = new FormData(document.getElementById("editComplaintForm"))
    const token = localStorage.getItem("token")
    const id = document.getElementById("editId").value

    form.append("_method", "PUT")
    form.append("status", status)

    axios.post(`/api/complaints/${id}`, form, {
        headers: { Authorization: `Bearer ${token}`, "Content-Type": "multipart/form-data" }
    }).then(() => {
        Swal.fire("Berhasil", "Pengaduan diperbarui", "success")
        bootstrap.Modal.getInstance(document.getElementById('editComplaintModal')).hide()
        fetchComplaints()
    })
}

const deleteComplaint = async (id) => {
    const token = localStorage.getItem("token")
    const confirm = await Swal.fire({ title: "Hapus pengaduan ini?", icon: "warning", showCancelButton: true })
    if (!confirm.isConfirmed) return

    await axios.delete(`/api/complaints/${id}`, { headers: { Authorization: `Bearer ${token}` } })
    Swal.fire("Terhapus", "", "success")
    fetchComplaints()
}

fetchComplaints()
</script>

@endsection
