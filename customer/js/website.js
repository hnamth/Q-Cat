function buatPesanan(){
    alert("Pesanan Berhasil Dibuat!")
}
function pesanKembali(){
    alert("Meninggalkan halaman? Semua data yang telah anda masukkan akan hilang")
}

function aturAlamat() {
    let radioAntar = document.getElementById("diAntar");
    let wadah = document.getElementById("wadahAlamat");
    let inputAlamat = document.getElementById("inputAlamat");

    if (radioAntar.checked) {
        wadah.style.display = "block";
        inputAlamat.required = true; 
    } else {
        wadah.style.display = "none";
        inputAlamat.value = ""; 
    }
}