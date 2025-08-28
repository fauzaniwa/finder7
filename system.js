// Tambahkan ini ke dalam file JavaScript Anda atau di dalam tag <script>
function toggleDropdown() {
    var dropdownMenu = document.getElementById('dropdownMenu');
    if (dropdownMenu.classList.contains('hidden')) {
        dropdownMenu.classList.remove('hidden');
        dropdownMenu.classList.add('block');
    } else {
        dropdownMenu.classList.remove('block');
        dropdownMenu.classList.add('hidden');
    }
}
