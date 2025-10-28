document.addEventListener("DOMContentLoaded", function () {
    let today = new Date().toISOString().split("T")[0];

    document.querySelectorAll(".start-date").forEach(function(start) {
        let end = start.closest('div.row')?.querySelector(".end-date");
        let startVal = start.value;
        let endVal = end ? end.value : null;

        start.min = startVal && startVal < today ? startVal : today;
        if(end) end.min = endVal && endVal < today ? endVal : today;

        start.addEventListener("change", function() {
            if(end) {
                end.min = this.value;
                if(end.value < this.value) {
                    end.value = this.value;
                }
            }
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    let now = new Date();
    let pad = (n) => n.toString().padStart(2, '0');
    let today = now.getFullYear() + '-' + pad(now.getMonth()+1) + '-' + pad(now.getDate()) + 'T' + pad(now.getHours()) + ':' + pad(now.getMinutes());

    let start = document.getElementById("start_datetime");
    let end   = document.getElementById("end_datetime");

    start.min = start.value && start.value < today ? start.value : today;
    end.min   = end.value && end.value < today ? end.value : today;

    start.addEventListener("change", function() {
        end.min = this.value;
        if(end.value < this.value) {
            end.value = this.value;
        }
    });
});

