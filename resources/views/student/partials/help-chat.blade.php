{{-- Floating help chatbot. Include once per student page, just before </body>. --}}
<style>
    #ecoHelp {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 1080;
        font-family: inherit;
    }

    #ecoHelpToggle {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        border: 0;
        cursor: pointer;
        background: var(--primary, #6C5CE7);
        color: #fff;
        font-size: 26px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .35);
    }

    #ecoHelpToggle:focus-visible,
    #ecoHelp button:focus-visible,
    #ecoHelpInput:focus-visible {
        outline: 3px solid #fff;
        outline-offset: 2px;
    }

    #ecoHelpPanel {
        position: absolute;
        right: 0;
        bottom: 72px;
        width: min(380px, calc(100vw - 32px));
        height: min(540px, calc(100vh - 120px));
        display: none;
        flex-direction: column;
        background: var(--surface, #1E293B);
        color: var(--text-main, #F8FAFC);
        border: 1px solid var(--border, rgba(255, 255, 255, .12));
        border-radius: 16px;
        box-shadow: 0 18px 48px rgba(0, 0, 0, .5);
        overflow: hidden;
    }

    #ecoHelp.open #ecoHelpPanel {
        display: flex;
    }

    #ecoHelpHead {
        padding: 12px 14px;
        background: var(--primary, #6C5CE7);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 700;
    }

    #ecoHelpHead small {
        display: block;
        font-weight: 400;
        opacity: .85;
    }

    #ecoHelpHead button {
        background: transparent;
        border: 0;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
    }

    #ecoHelpMsgs {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .eh-msg {
        max-width: 88%;
        padding: 9px 12px;
        border-radius: 14px;
        font-size: 14px;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .eh-bot {
        align-self: flex-start;
        background: var(--surface-alt, #27354D);
        border-bottom-left-radius: 4px;
    }

    .eh-user {
        align-self: flex-end;
        background: var(--primary, #6C5CE7);
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .eh-msg p {
        margin: 0 0 6px;
    }

    .eh-msg p:last-child {
        margin: 0;
    }

    .eh-msg ul,
    .eh-msg ol {
        margin: 4px 0 6px 18px;
        padding: 0;
    }

    .eh-msg pre {
        background: rgba(0, 0, 0, .35);
        padding: 8px;
        border-radius: 8px;
        overflow-x: auto;
        font-size: 12.5px;
        margin: 6px 0;
    }

    .eh-msg code {
        background: rgba(0, 0, 0, .3);
        padding: 1px 4px;
        border-radius: 4px;
    }

    .eh-msg pre code {
        background: none;
        padding: 0;
    }

    #ecoHelpChips {
        padding: 0 12px 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    #ecoHelpChips button {
        font-size: 12.5px;
        padding: 5px 10px;
        border-radius: 999px;
        cursor: pointer;
        background: transparent;
        color: inherit;
        border: 1px solid var(--border, rgba(255, 255, 255, .25));
    }

    #ecoHelpForm {
        display: flex;
        gap: 8px;
        padding: 10px;
        border-top: 1px solid var(--border, rgba(255, 255, 255, .12));
    }

    #ecoHelpInput {
        flex: 1;
        border-radius: 10px;
        border: 1px solid var(--border, rgba(255, 255, 255, .2));
        background: rgba(0, 0, 0, .2);
        color: inherit;
        padding: 8px 10px;
    }

    #ecoHelpSend {
        border: 0;
        border-radius: 10px;
        padding: 0 14px;
        background: var(--primary, #6C5CE7);
        color: #fff;
        cursor: pointer;
    }

    #ecoHelpSend:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .eh-typing span {
        display: inline-block;
        width: 6px;
        height: 6px;
        margin: 0 2px;
        border-radius: 50%;
        background: currentColor;
        opacity: .5;
        animation: ehBlink 1s infinite;
    }

    .eh-typing span:nth-child(2) {
        animation-delay: .2s;
    }

    .eh-typing span:nth-child(3) {
        animation-delay: .4s;
    }

    @keyframes ehBlink {

        0%,
        80%,
        100% {
            opacity: .2;
        }

        40% {
            opacity: 1;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .eh-typing span {
            animation: none;
        }
    }
</style>

<div id="ecoHelp">
    <div id="ecoHelpPanel" role="dialog" aria-label="EcoQuest help assistant">
        <div id="ecoHelpHead">
            <div>EcoQuest Helper <small>Ask about lessons or how to use the app</small></div>
            <button type="button" id="ecoHelpClose" aria-label="Close help chat">&times;</button>
        </div>
        <div id="ecoHelpMsgs" aria-live="polite"></div>
        <div id="ecoHelpChips"></div>
        <form id="ecoHelpForm" autocomplete="off">
            <input id="ecoHelpInput" type="text" maxlength="1000" placeholder="Type your question..." aria-label="Your question" required>
            <button id="ecoHelpSend" type="submit">Send</button>
        </form>
    </div>
    <button type="button" id="ecoHelpToggle" aria-label="Open help chat" aria-expanded="false">💬</button>
</div>

<script>
    (() => {
        const root = document.getElementById('ecoHelp');
        const toggle = document.getElementById('ecoHelpToggle');
        const closeBtn = document.getElementById('ecoHelpClose');
        const msgs = document.getElementById('ecoHelpMsgs');
        const chips = document.getElementById('ecoHelpChips');
        const form = document.getElementById('ecoHelpForm');
        const input = document.getElementById('ecoHelpInput');
        const send = document.getElementById('ecoHelpSend');
        const url = @json(route('student.help-chat'));
        const csrf = @json(csrf_token());
        const KEY = 'ecoHelpHistory';
        const suggestions = ['How do I start a quest?', 'How do I earn XP?', 'Explain my recommended topics', 'Give me a study tip'];
        let history = [];

        try {
            history = JSON.parse(sessionStorage.getItem(KEY) || '[]');
        } catch (e) {
            history = [];
        }
        if (!Array.isArray(history)) history = [];

        const esc = s => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

        // Tiny, safe markdown: escape first, then format.
        function render(text) {
            const blocks = [];
            let t = esc(text).replace(/```(?:\w+)?\n?([\s\S]*?)```/g, (_, code) => {
                blocks.push('<pre><code>' + code.replace(/\n$/, '') + '</code></pre>');
                return '\u0000' + (blocks.length - 1) + '\u0000';
            });
            t = t.replace(/`([^`\n]+)`/g, '<code>$1</code>')
                .replace(/\*\*([^*\n]+)\*\*/g, '<strong>$1</strong>');
            const out = [];
            let list = null;
            t.split('\n').forEach(line => {
                const ul = line.match(/^\s*[-*•]\s+(.*)/),
                    ol = line.match(/^\s*\d+[.)]\s+(.*)/);
                const kind = ul ? 'ul' : ol ? 'ol' : null;
                if (kind) {
                    if (list !== kind) {
                        if (list) out.push('</' + list + '>');
                        out.push('<' + kind + '>');
                        list = kind;
                    }
                    out.push('<li>' + (ul || ol)[1] + '</li>');
                } else {
                    if (list) {
                        out.push('</' + list + '>');
                        list = null;
                    }
                    if (line.trim()) out.push(/^\u0000\d+\u0000$/.test(line.trim()) ? line.trim() : '<p>' + line + '</p>');
                }
            });
            if (list) out.push('</' + list + '>');
            return out.join('').replace(/\u0000(\d+)\u0000/g, (_, i) => blocks[i]);
        }

        function add(role, text, html) {
            const el = document.createElement('div');
            el.className = 'eh-msg ' + (role === 'user' ? 'eh-user' : 'eh-bot');
            if (role === 'user') el.textContent = text;
            else el.innerHTML = html || render(text);
            msgs.appendChild(el);
            msgs.scrollTop = msgs.scrollHeight;
            return el;
        }

        function save() {
            history = history.slice(-10);
            try {
                sessionStorage.setItem(KEY, JSON.stringify(history));
            } catch (e) {}
        }

        function open(state) {
            root.classList.toggle('open', state);
            toggle.setAttribute('aria-expanded', state ? 'true' : 'false');
            if (state) input.focus();
        }

        async function ask(message) {
            chips.style.display = 'none';
            add('user', message);
            const typing = add('bot', '', '<span class="eh-typing" aria-label="Typing"><span></span><span></span><span></span></span>');
            send.disabled = true;
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        message,
                        history
                    })
                });
                const data = await res.json().catch(() => ({}));
                typing.remove();
                if (res.status === 429) {
                    add('bot', 'You are sending messages too fast. Please wait a moment.');
                    return;
                }
                if (!res.ok || !data.success) {
                    add('bot', data.message || 'Sorry, something went wrong. Please try again.');
                    return;
                }
                add('bot', data.reply);
                history.push({
                    role: 'user',
                    content: message
                }, {
                    role: 'assistant',
                    content: data.reply
                });
                save();
            } catch (e) {
                typing.remove();
                add('bot', 'I could not reach the server. Check your connection and try again.');
            } finally {
                send.disabled = false;
                input.focus();
            }
        }

        toggle.addEventListener('click', () => open(!root.classList.contains('open')));
        closeBtn.addEventListener('click', () => {
            open(false);
            toggle.focus();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && root.classList.contains('open')) open(false);
        });
        form.addEventListener('submit', e => {
            e.preventDefault();
            const m = input.value.trim();
            if (!m || send.disabled) return;
            input.value = '';
            ask(m);
        });

        if (history.length) {
            history.forEach(h => add(h.role === 'user' ? 'user' : 'bot', h.content));
            chips.style.display = 'none';
        } else {
            add('bot', "Hi! I'm your EcoQuest Helper. Ask me about your lessons, your recommended topics, or how to use the app.");
            suggestions.forEach(s => {
                const b = document.createElement('button');
                b.type = 'button';
                b.textContent = s;
                b.addEventListener('click', () => ask(s));
                chips.appendChild(b);
            });
        }
    })();
</script>