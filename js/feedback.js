document.addEventListener("DOMContentLoaded", () => {

    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    const table = document.getElementById("feedbackTable").getElementsByTagName("tbody")[0];

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;

        for (let row of table.rows) {
            const farmer = row.cells[1].innerText.toLowerCase();
            const concern = row.cells[2].innerText.toLowerCase();
            const status = row.cells[3].innerText;

            const matchesSearch = farmer.includes(searchValue) || concern.includes(searchValue);
            const matchesStatus = statusValue === "" || status === statusValue;

            row.style.display = (matchesSearch && matchesStatus) ? "" : "none";
        }
    }

    searchInput.addEventListener("keyup", filterTable);
    statusFilter.addEventListener("change", filterTable);

});
