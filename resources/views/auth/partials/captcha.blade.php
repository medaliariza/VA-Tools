<div class="field-full captcha-field">
    <label>{{ $captcha['prompt'] }}</label>
    <div class="captcha-game" data-captcha-game data-rotation="{{ $captcha['start_rotation'] }}">
        <input data-captcha-input type="hidden" name="captcha_rotation" value="{{ $captcha['start_rotation'] }}">
        <div class="captcha-stage">
            <div
                class="captcha-piece captcha-piece-{{ $captcha['piece']['shape'] }}"
                data-captcha-piece
                style="transform: rotate({{ $captcha['start_rotation'] }}deg);"
                aria-live="polite"
            >
                <span>{{ $captcha['piece']['label'] }}</span>
            </div>
            <div class="captcha-slot" aria-hidden="true"></div>
        </div>
        <div class="captcha-controls">
            <button type="button" class="button-light button-small" data-captcha-rotate="-90">Rotate Left</button>
            <button type="button" class="button-light button-small" data-captcha-rotate="90">Rotate Right</button>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[data-captcha-game]').forEach((game) => {
        const piece = game.querySelector('[data-captcha-piece]');
        const input = game.querySelector('[data-captcha-input]');

        game.querySelectorAll('[data-captcha-rotate]').forEach((button) => {
            button.addEventListener('click', () => {
                const change = Number(button.dataset.captchaRotate);
                const rotation = (Number(input.value) + change + 360) % 360;

                input.value = String(rotation);
                piece.style.transform = `rotate(${rotation}deg)`;
            });
        });
    });
</script>
