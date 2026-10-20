document.addEventListener('DOMContentLoaded', function () {
  var menu = document.querySelector('[data-menu-toggle]');
  var sidebar = document.getElementById('sidebar');
  if (menu && sidebar) menu.addEventListener('click', function () { sidebar.classList.toggle('open'); });
  var toggle = document.querySelector('[data-password-toggle]');
  var password = document.getElementById('password');
  if (toggle && password) toggle.addEventListener('click', function () { var visible = password.type === 'text'; password.type = visible ? 'password' : 'text'; toggle.textContent = visible ? 'Lihat' : 'Sembunyikan'; });
});
