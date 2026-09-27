(function($) {
    "use strict";

    const sidebar = document.querySelector(".sidebar");
    const sidebarToggle = document.querySelector(".sidebar-toggle-btn");

    if (sidebar) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("show");
        });
    }

    let codes = document.querySelectorAll('.code');
    if (codes) {
        codes.forEach(codeElement => {
            let code = codeElement.closest('div');
            const clipboard = new ClipboardJS(code, {
                target: () => code.querySelector('.copy-data')
            });
            clipboard.on("success", (e) => {
                e.clearSelection();
                const copyButton = code.querySelector('.copy');
                copyButton.innerHTML = '<i class="fa fa-check"></i>';
                copyButton.classList.add("copied");
                setTimeout(() => {
                    copyButton.innerHTML = '<i class="far fa-clone"></i>';
                    copyButton.classList.remove("copied");
                }, 500);
            });
        });
    }
})(jQuery);