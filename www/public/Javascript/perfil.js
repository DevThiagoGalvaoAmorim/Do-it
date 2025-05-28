const toggleBtn = document.querySelector(".toggle-btn");
const sidebar = document.querySelector(".sidebar");
toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("expanded");
  content.classList.toggle("pushed");
});