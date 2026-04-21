document.querySelectorAll('form').forEach(form => {
  form.addEventListener('submit', () => {
    const button = form.querySelector('button[type="submit"]');
    if (button) {
      button.disabled = true;
      button.dataset.originalText = button.innerHTML;
      button.innerHTML = 'Processing...';
      setTimeout(() => {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText || 'Submit';
      }, 4000);
    }
  });
});
