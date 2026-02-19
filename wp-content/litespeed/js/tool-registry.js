document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('toolSearch');
  const toolItems = Array.from(document.querySelectorAll('#toolList .tool'));

  if (!searchInput || !toolItems.length) {
    return;
  }

  const normalize = (value) => (value || '').toLowerCase();

  searchInput.addEventListener('input', () => {
    const query = normalize(searchInput.value.trim());

    for (const tool of toolItems) {
      const text = normalize(tool.innerText);
      const tags = normalize(tool.getAttribute('data-tags'));
      const shouldShow = !query || text.includes(query) || tags.includes(query);
      tool.style.display = shouldShow ? '' : 'none';
    }
  });
});
