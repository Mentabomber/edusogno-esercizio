function togglePasswordVisibility(toggleElement, targetElement) {
    toggleElement.addEventListener("click", function () {
        // Toggle the type attribute
        const type = targetElement.getAttribute("type") === "password" ? "text" : "password";
        targetElement.setAttribute("type", type);
    });
}
