document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('shorten-form');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();


        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Encurtando...';
        submitBtn.disabled = true;

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Erro ao encurtar o link.');
            }

            form.reset();

            await navigator.clipboard.writeText(data.short_url);

            submitBtn.textContent = 'Copiado!';
            setTimeout(() => {
                submitBtn.textContent = originalText;
            }, 2000);
        } catch (err) {
            submitBtn.textContent = originalText;
        } finally {
            submitBtn.disabled = false;
        }
    });
});
