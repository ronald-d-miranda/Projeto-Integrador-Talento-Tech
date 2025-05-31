document.addEventListener('DOMContentLoaded', () => {
  const modal = document.querySelector('.modal');
  const modalContent = document.querySelector('.modal-content');
  const modalClose = document.querySelector('.modal-close');
  const modalTitle = document.querySelector('.modal-title');
  const modalText = document.querySelector('.modal-text');

  document.querySelectorAll('.btn').forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();

      if (button.textContent.includes('Histórico')) {
        modalTitle.textContent = 'Histórico de Exames';
        modalText.textContent = 'Aqui você verá todos os exames realizados anteriormente.';
      } else if (button.textContent.includes('Falar com Doutores')) {
        modalTitle.textContent = 'Falar com Doutores';
        modalText.textContent = 'Inicie uma conversa com seu médico de confiança.';
      } else {
        return; // outros botões não abrem modal
      }

      modal.classList.add('active');
    });
  });

  modalClose.addEventListener('click', () => {
    modal.classList.remove('active');
  });

  // Fechar modal ao clicar fora do conteúdo
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('active');
    }
  });
});
