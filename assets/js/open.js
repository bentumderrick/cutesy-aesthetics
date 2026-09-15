
const receive = document.querySelector('.pop img');
const images = document.querySelectorAll('.img1');
images.forEach(img => {
  img.addEventListener("click", function(){
const fileName = this.dataset.src;
receive.src = fileName;
document.querySelector('.pop').classList.remove('pop-out')
document.querySelector('.pop').classList.add('pop-in')
document.body.classList.add('no-scroll');
})
});
document.querySelector('.pop').addEventListener("click", function(e){
if (e.target === e.currentTarget) {
document.querySelector('.pop').classList.remove('pop-in')

  document.querySelector('.pop').classList.add('pop-out')
document.body.classList.remove('no-scroll'); //
}
})
document.addEventListener('keydown', e => {
  if(e.key === 'Escape' && pop.classList.contains('pop-in')) {
    pop.classList.remove('pop-in');
    pop.classList.add('pop-out');
    document.body.classList.remove('no-scroll');
  }
});