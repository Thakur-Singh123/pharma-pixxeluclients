<!--start css message-->
<style>
.notification-box {
    position: fixed;
    top: 80px;
    right: 30px;
    z-index: 9999;
    padding: 10px 20px;
    border-radius: 10px;
    color: #fff;
    font-size: 13px;
    min-width: 270px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
.notification-success {
    background-color: #28a745;
}
.notification-error {
    background-color: #dc3545;
}
.close-btn {
    position: absolute;
    top: 8px;
    right: 12px;
    font-size: 20px;
    cursor: pointer;
    color: red;
    user-select: none;
}
</style>
<!--end css message-->
<!--satrt success message-->
@if (Session::has('success'))
<div class="notification-box notification-success" id="notifBox">
    <strong></strong> {{ Session::get('success') }}
    <span class="close-btn" onclick="document.getElementById('notifBox').style.display='none'">
      &times; <span id="countdown" style="color: #0d0e0cff; font-weight: bold;">(5)</span>
    </span>
</div>
@endif
<!--end success message-->
<!-- start error message-->
@if (Session::has('unsuccess'))
<div class="notification-box notification-error" id="notifBox">
    <strong></strong> {{ Session::get('unsuccess') }}
    <span class="close-btn" onclick="document.getElementById('notifBox').style.display='none'">
      &times; <span id="countdown" style="color: #90EE90; font-weight: bold;">(5)</span>
    </span>
</div>
@endif
<!-- end error message-->
<!-- start js message-->
<script>
window.onload = function() {
    let countdownElement = document.getElementById('countdown');
    let notifBox = document.getElementById('notifBox');
    if (notifBox && countdownElement) {
        let timeLeft = 5;
        let timerId = setInterval(() => {
            timeLeft--;
            if(timeLeft <= 0) {
                clearInterval(timerId);
                notifBox.style.display = 'none';
            } else {
                countdownElement.textContent = `(${timeLeft})`;
            }
        }, 1000);
    }
}
</script>
<!--end js message-->
