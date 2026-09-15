const ajax = document.getElementById("ajax");
const routes = {
  feed: "feed-content.php",
  gallery: "gallery-content.php",
  crochet: "crochet-content.php",
  shop: "shop-content.php",
  about: "about-content.php",
  blog: "blog-content.php"
}
function loadPage(pageName) {
  const file = routes[ pageName ];
  if (!file) {
    alert("Unknown page: " + pageName);
    return;
  }



    fetch(file)
      .then(response => response.text())
      .then(html => {
      ajax.innerHTML = html;
        setActiveLink(pageName);
      })
  
  .catch (error => {
   
  });
}

function setActiveLink(pageName){
    document.querySelectorAll("[data-page]").forEach(link => {
        link.classList.toggle("selected", link.dataset.page === pageName);
    });
}
function getPageFromURL(){
  const params = new URLSearchParams(window.location.search);
  return params.get("page");
}
const initialPage = getPageFromURL() || "feed";
loadPage(initialPage);
document.querySelectorAll("[data-page]").forEach(link =>{
  link.addEventListener("click", e =>{
    e.preventDefault();
    const pageName = link.dataset.page;
    history.pushState({page: pageName}, "", "?page="+ pageName);
    loadPage(pageName);
  })
})
window.addEventListener("popstate", () => {
  const pageName = getPageFromURL() || "feed";
  loadPage(pageName);
})