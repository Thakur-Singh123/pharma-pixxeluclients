//Enter phone no
document.addEventListener("DOMContentLoaded", function () {
    const mobileInput = document.getElementById('mobile');
    //Check if the mobile input exists on the page
    if (mobileInput) {
        mobileInput.addEventListener('input', function (e) {
            let value = this.value.replace(/\D/g, "");
            if (value.length > 5) {
                value = value.slice(0, 5) + "-" + value.slice(5, 10);
            }
            this.value = value.slice(0, 11);
        });
    }
});

    //start thumbnail upload image and slider images
    //thumbnail upload image
    const thumbnailInput = document.getElementById("thumbnailInput");
    const thumbnailPreview = document.getElementById("thumbnailPreview");
    const thumbnailCounter = document.getElementById("thumbnailCounter");
    const thumbnailText = document.getElementById("thumbnailText");
    const removeThumbnailIcon = document.getElementById("removeThumbnailIcon");
    const thumbnailLabel = document.getElementById("thumbnailLabel");

    thumbnailInput.addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (!file || !file.type.startsWith("image/")) {
            thumbnailCounter.innerText = "Invalid file!";
            thumbnailCounter.style.color = "red";
            return;
        }

        thumbnailCounter.innerText = "0%";
        thumbnailCounter.style.color = "green";
        thumbnailText.style.display = "none";

        let count = 0;
        const interval = setInterval(() => {
        count++;
        thumbnailCounter.innerText = count + "%";
            if (count >= 100) {
                clearInterval(interval);
                thumbnailCounter.innerText = "File upload completed 100%";
                thumbnailCounter.style.color = "green";

                const reader = new FileReader();
                reader.onload = function (event) {
                thumbnailPreview.src = event.target.result;
                thumbnailPreview.style.display = "block";
                removeThumbnailIcon.style.display = "flex";
                };
                reader.readAsDataURL(file);
            }
        }, 20);
    });

    removeThumbnailIcon.addEventListener("click", () => {
        thumbnailInput.value = "";
        thumbnailPreview.style.display = "none";
        thumbnailPreview.src = "#";
        thumbnailCounter.innerText = "";
        thumbnailText.style.display = "block";
        removeThumbnailIcon.style.display = "none";
    });

    //slider upload images
    const sliderInput = document.getElementById("sliderInput");
    const sliderPreviewContainer = document.getElementById("sliderPreviewContainer");
    const sliderCounter = document.getElementById("sliderCounter");
    const sliderText = document.getElementById("sliderText");

    let selectedFiles = [];

    sliderInput.addEventListener("change", function (e) {
        const newFiles = Array.from(e.target.files);
        selectedFiles = selectedFiles.concat(newFiles);
        updatePreview();
    });

    function updatePreview() {
        sliderPreviewContainer.innerHTML = "";

        if (selectedFiles.length === 0) {
            sliderText.style.display = "block";
            sliderCounter.innerText = "";
            return;
        } else {
            sliderText.style.display = "none";
        }

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (event) {
                const imageBox = document.createElement("div");
                imageBox.classList.add("image-box");

                const img = document.createElement("img");
                img.src = event.target.result;
                img.classList.add("preview-img");

                const removeIcon = document.createElement("span");
                removeIcon.classList.add("remove-icon");
                removeIcon.innerHTML = '<i class="fas fa-trash"></i>';
                removeIcon.title = "Remove this image";

                removeIcon.addEventListener("click", () => {
                    selectedFiles.splice(index, 1);
                    updatePreview();
                });

                imageBox.appendChild(img);
                imageBox.appendChild(removeIcon);
                sliderPreviewContainer.appendChild(imageBox);
            };
            reader.readAsDataURL(file);
        });

        sliderCounter.innerText = `${selectedFiles.length} image(s) selected`;
        sliderCounter.style.color = "green";
    }
//end thumbnail upload image and slider images


document.addEventListener("DOMContentLoaded", function () {
    const iconInputBlog = document.getElementById("iconInputBlog");
    const iconPreviewBlog = document.getElementById("iconPreviewBlog");
    const iconCounterBlog = document.getElementById("iconCounterBlog");
    const iconTextBlog = document.getElementById("iconTextBlog");
    const removeIconBtnBlog = document.getElementById("removeIconBtnBlog");

    if (iconInputBlog) {
        iconInputBlog.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith("image/")) {
                iconCounterBlog.innerText = "Invalid file!";
                iconCounterBlog.style.color = "red";
                return;
            }

            iconCounterBlog.innerText = "0%";
            iconCounterBlog.style.color = "green";
            iconCounterBlog.style.display = "block";

            let count = 0;
            const interval = setInterval(() => {
                count++;
                iconCounterBlog.innerText = count + "%";
                if (count >= 100) {
                    clearInterval(interval);
                    iconCounterBlog.innerText = "File upload completed 100%";
                    iconCounterBlog.style.color = "green";

                    const reader = new FileReader();
                    reader.onload = function (event) {
                        iconPreviewBlog.src = event.target.result;
                        iconPreviewBlog.style.display = "block";
                        iconTextBlog.style.display = "none";
                        removeIconBtnBlog.style.display = "flex";
                    };
                    reader.readAsDataURL(file);
                }
            }, 20);
        });

        removeIconBtnBlog.addEventListener("click", () => {
            iconInputBlog.value = "";
            iconPreviewBlog.src = "#";
            iconPreviewBlog.style.display = "none";
            iconCounterBlog.innerText = "";
            iconTextBlog.style.display = "block";
            removeIconBtnBlog.style.display = "none";
        });
    }
});



