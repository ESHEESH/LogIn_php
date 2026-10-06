// Clear the red error state as soon as the user starts typing again
document.querySelectorAll('input.invalid').forEach(function (input) {
  input.addEventListener('input', function () {
    input.classList.remove('invalid');
    var msg = input.nextElementSibling;
    if (msg && msg.classList.contains('msg')) msg.remove();
  });
});
