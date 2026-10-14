<x-guest-layout>
    <style>
        :root {
            --espresso: #2B211B; --terracotta: #C86B45; --terracotta-dark: #AD5634;
            --cream: #FFF9F2; --background: #F8F5F0; --text: #332820; --muted: #6F655C;
            --border: #E9DFD5; --danger: #C0392B; --ok: #2E8B57; --field: #FFFFFF;
            --heading: #2B211B; --ease: cubic-bezier(.2, .8, .2, 1); --pop: cubic-bezier(.34, 1.56, .64, 1);
        }
        @media (prefers-color-scheme: dark) {
            :root { --cream: #231B16; --background: #151010; --text: #F1E8DF; --muted: #B8ABA0; --border: #3A2E27; --field: #2E241E; --heading: #F6EDE4; }
        }
        :root[data-theme="dark"] { --cream: #231B16; --background: #151010; --text: #F1E8DF; --muted: #B8ABA0; --border: #3A2E27; --field: #2E241E; --heading: #F6EDE4; }
        :root[data-theme="light"] { --cream: #FFF9F2; --background: #F8F5F0; --text: #332820; --muted: #6F655C; --border: #E9DFD5; --field: #FFFFFF; --heading: #2B211B; }
        html.theme-anim *, html.theme-anim *::before { transition: background-color .45s, color .45s, border-color .45s !important; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { background: var(--background) !important; color: var(--text); }
        ::selection { background: rgba(200, 107, 69, .3); }
        svg.i { width: 1em; height: 1em; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }
        .skip { position: absolute; left: 12px; top: -60px; z-index: 50; padding: 8px 14px; border-radius: 8px; background: var(--espresso); color: #fff; font-size: 13px; transition: top .2s; }
        .skip:focus { top: 12px; }

        /* PAGE + SHELL */
        @property --ang { syntax: '<angle>'; inherits: false; initial-value: 0deg; }
        .login-page {
            min-height: 100dvh; display: grid; place-items: center; font-family: 'Inter', system-ui, sans-serif;
            padding: max(16px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(16px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left));
            background: radial-gradient(circle at 15% 20%, rgba(200, 107, 69, .10), transparent 40%), radial-gradient(circle at 85% 80%, rgba(200, 107, 69, .08), transparent 40%), radial-gradient(rgba(43, 33, 27, .06) 1px, transparent 1px) 0 0 / 22px 22px;
        }
        .blob { position: fixed; z-index: 0; width: 420px; height: 420px; border-radius: 50%; filter: blur(70px); opacity: .32; pointer-events: none; animation: blob 18s ease-in-out infinite alternate; }
        .b1 { top: -120px; left: -100px; background: #E9A17E; }
        .b2 { bottom: -140px; right: -120px; background: #C86B45; animation-delay: -9s; }
        @keyframes blob { 50% { transform: translate(60px, 40px) scale(1.15); border-radius: 45% 55% 60% 40%; } to { transform: translate(-30px, 60px) scale(.95); } }
        .login-shell {
            position: relative; z-index: 1; width: 100%; max-width: 1020px; max-height: calc(100dvh - 32px);
            display: grid; grid-template-columns: .9fr 1.1fr; overflow: hidden;
            border: 1px solid var(--border); border-radius: 26px; background: var(--cream);
            box-shadow: 0 2px 4px rgba(43, 33, 27, .04), 0 12px 30px rgba(43, 33, 27, .08), 0 30px 80px rgba(43, 33, 27, .10);
            animation: shellIn .8s var(--ease) both;
        }
        .login-shell::before { content: ""; position: absolute; inset: 0; z-index: 3; border-radius: inherit; padding: 1.5px; pointer-events: none; background: conic-gradient(from var(--ang), transparent 0 72%, rgba(200, 107, 69, .9), transparent); -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite: xor; mask-composite: exclude; animation: ang 7s linear infinite; }
        @keyframes ang { to { --ang: 360deg; } }
        @keyframes shellIn { from { opacity: 0; transform: translateY(24px) scale(.98); filter: blur(8px); } to { opacity: 1; transform: none; filter: none; } }
        .login-shell.done { animation: shellOut .7s var(--ease) forwards; }
        @keyframes shellOut { to { transform: scale(.94); filter: blur(4px); opacity: .5; } }
        .login-shell.leaving { animation: leave .35s var(--ease) forwards; }
        @keyframes leave { to { opacity: 0; transform: translateY(-14px) scale(.98); filter: blur(4px); } }
        .reveal { opacity: 0; animation: rise .65s var(--ease) both; animation-delay: calc(var(--i, 0) * 70ms + 150ms); }
        .reveal.revealed { opacity: 1; animation: none; }
        @keyframes rise { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
        .has-errors .login-shell, .has-errors .reveal { animation: none; opacity: 1; }

        /* LEFT PANEL */
        .login-aside { position: relative; overflow: hidden; padding: 40px 36px; color: var(--cream); background: linear-gradient(135deg, #2B211B, #3a2a21 55%, #2B211B); background-size: 200% 200%; animation: bgShift 14s ease-in-out infinite alternate; display: flex; flex-direction: column; justify-content: space-between; gap: 20px; }
        @keyframes bgShift { to { background-position: 100% 100%; } }
        .login-aside::before, .login-aside::after { content: ""; position: absolute; border-radius: 50%; border: 1px solid rgba(200, 107, 69, .35); transform: translate(calc(var(--px, 0) * 1px), calc(var(--py, 0) * 1px)); transition: transform .3s ease-out; animation: ring 8s ease-in-out infinite alternate; }
        .login-aside::before { width: 320px; height: 320px; top: -160px; right: -180px; box-shadow: 0 0 0 30px rgba(200, 107, 69, .04), 0 0 0 60px rgba(200, 107, 69, .03); }
        .login-aside::after { width: 230px; height: 230px; left: -140px; bottom: 90px; animation-delay: -4s; }
        @keyframes ring { to { scale: 1.1; } }
        .spot { position: absolute; inset: 0; pointer-events: none; background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 30%), rgba(200, 107, 69, .20), transparent 70%); }
        .grain { position: absolute; inset: 0; opacity: .05; pointer-events: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence baseFrequency='.8' numOctaves='2'/%3E%3C/filter%3E%3Crect width='120' height='120' filter='url(%23n)'/%3E%3C/svg%3E"); }
        .sparkles { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
        .sparkles span { position: absolute; bottom: -10px; width: 6px; height: 6px; border-radius: 50%; background: #E9A17E; opacity: 0; animation: sparkUp 9s linear infinite; }
        .sparkles span:nth-child(1) { left: 12%; } .sparkles span:nth-child(2) { left: 28%; animation-delay: -2s; width: 4px; height: 4px; }
        .sparkles span:nth-child(3) { left: 46%; animation-delay: -4s; } .sparkles span:nth-child(4) { left: 63%; animation-delay: -6s; width: 8px; height: 8px; }
        .sparkles span:nth-child(5) { left: 78%; animation-delay: -3s; width: 4px; height: 4px; } .sparkles span:nth-child(6) { left: 90%; animation-delay: -7s; }
        @keyframes sparkUp { 10% { opacity: .7; } 100% { transform: translate(20px, -720px); opacity: 0; } }
        .brand, .aside-content, .benefits, .aside-footer { position: relative; z-index: 1; }
        .brand-icon { width: 54px; height: 54px; display: grid; place-items: center; margin-bottom: 14px; border: 1px solid rgba(200, 107, 69, .45); border-radius: 16px; background: rgba(200, 107, 69, .13); color: #E9A17E; font-size: 27px; animation: float 4s ease-in-out infinite, iconGlow 3s ease-out infinite; }
        @keyframes float { 50% { transform: translateY(-6px); } }
        @keyframes iconGlow { 0% { box-shadow: 0 0 0 0 rgba(200, 107, 69, .35); } 100% { box-shadow: 0 0 0 16px rgba(200, 107, 69, 0); } }
        .brand-name { font-family: Georgia, serif; font-size: 29px; font-weight: 700; letter-spacing: -1px; background: linear-gradient(100deg, #FFF9F2 40%, #E9A17E 50%, #FFF9F2 60%) 200% 0 / 250% 100%; -webkit-background-clip: text; background-clip: text; color: transparent; animation: shimmer 6s linear infinite; }
        @keyframes shimmer { to { background-position: -100% 0; } }
        .brand-subtitle { margin-top: 8px; color: #D7B9A6; font-size: 11px; letter-spacing: 4px; text-transform: uppercase; transition: letter-spacing .4s var(--ease); }
        .brand:hover .brand-subtitle { letter-spacing: 5px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 8px; color: var(--terracotta); font-size: 12px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; }
        .eyebrow::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; animation: dot 2s ease-in-out infinite; }
        @keyframes dot { 50% { box-shadow: 0 0 0 6px rgba(200, 107, 69, .15); opacity: .6; } }
        .aside-content h1 { margin: 14px 0; font-family: Georgia, serif; font-size: clamp(30px, 3.2vw, 42px); line-height: 1.2; letter-spacing: -1px; color: var(--cream); }
        .grad { background: linear-gradient(90deg, #E9A17E, #FFF9F2); background-size: 200% 100%; -webkit-background-clip: text; background-clip: text; color: transparent; animation: gradMove 5s ease-in-out infinite alternate; }
        @keyframes gradMove { to { background-position: 100% 0; } }
        .aside-content p { max-width: 330px; color: #D3C7BD; font-size: 14px; line-height: 1.8; }
        .benefits { display: grid; gap: 12px; }
        .benefit { display: flex; align-items: center; gap: 14px; padding: 9px 12px; border: 1px solid rgba(255, 255, 255, .06); border-radius: 14px; background: rgba(255, 255, 255, .04); backdrop-filter: blur(10px); color: #EDE1D7; font-size: 13px; transition: transform .3s var(--ease), background .3s, border-color .3s; }
        .benefit-icon { width: 40px; height: 40px; flex-shrink: 0; display: grid; place-items: center; border: 1px solid rgba(200, 107, 69, .45); border-radius: 12px; color: #E9A17E; font-size: 18px; transition: transform .35s var(--pop), box-shadow .3s; }
        .aside-footer { color: #BFB1A6; font-size: 12px; letter-spacing: .5px; }
        @media (hover: hover) {
            .benefit:hover { transform: translateX(6px); background: rgba(255, 255, 255, .08); border-color: rgba(200, 107, 69, .4); }
            .benefit:hover .benefit-icon { transform: rotate(-8deg) scale(1.1); box-shadow: 0 0 18px rgba(200, 107, 69, .35); }
        }

        /* RIGHT PANEL */
        .login-main { position: relative; isolation: isolate; min-width: 0; padding: 40px clamp(24px, 4.5vw, 58px); display: flex; flex-direction: column; justify-content: center; overflow-y: auto; scrollbar-width: thin; }
        .login-main::before { content: ""; position: absolute; inset: 0; z-index: -1; pointer-events: none; background: radial-gradient(320px circle at var(--fx, 70%) var(--fy, 20%), rgba(200, 107, 69, .08), transparent 70%); }
        .head-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .theme-btn { width: 32px; height: 32px; display: grid; place-items: center; border: 1px solid var(--border); border-radius: 10px; background: var(--field); color: var(--muted); font-size: 16px; cursor: pointer; transition: transform .4s var(--pop), color .2s, border-color .2s; }
        .theme-btn:hover { color: var(--terracotta); border-color: var(--terracotta); transform: rotate(180deg); }
        .theme-btn:focus-visible, .password-toggle:focus-visible, .register-button:focus-visible { outline: 2px solid var(--terracotta); outline-offset: 2px; }
        .form-heading { margin-bottom: 22px; }
        .form-heading h2 { position: relative; display: inline-block; margin: 10px 0 8px; color: var(--heading); font-family: Georgia, serif; font-size: clamp(28px, 3vw, 36px); line-height: 1.2; letter-spacing: -1px; }
        .form-heading h2::after { content: ""; position: absolute; left: 0; bottom: -4px; width: 100%; height: 3px; border-radius: 3px; background: var(--terracotta); transform: scaleX(0); transform-origin: left; animation: draw .8s .7s var(--ease) forwards; }
        .has-errors .form-heading h2::after { animation: none; transform: scaleX(1); }
        @keyframes draw { to { transform: scaleX(1); } }
        .form-heading p { margin: 0; color: var(--muted); font-size: 14px; }

        .status-box { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding: 11px 14px; border: 1px solid rgba(46, 139, 87, .35); border-left: 4px solid var(--ok); border-radius: 11px; background: rgba(46, 139, 87, .10); color: var(--ok); font-size: 13px; font-weight: 600; animation: rise .5s var(--ease) both; }

        /* FIELDS */
        .field-group { position: relative; margin-bottom: 16px; }
        .label-row { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 7px; }
        .field-label { display: inline-block; color: var(--text); font-size: 12px; font-weight: 700; transition: color .2s, transform .25s var(--ease); }
        .field-group:focus-within .field-label { color: var(--terracotta-dark); transform: translateX(3px); }
        .label-row .field-label + * { margin-left: auto; }
        .field { position: relative; }
        .field::after { content: ""; position: absolute; left: 12px; right: 12px; bottom: 0; height: 2px; border-radius: 2px; background: var(--terracotta); transform: scaleX(0); transition: transform .3s var(--ease); pointer-events: none; }
        .field:focus-within::after { transform: scaleX(1); }
        .field .ico { position: absolute; left: 14px; top: 50%; width: 17px; height: 17px; transform: translateY(-50%); color: #9A8D83; pointer-events: none; transition: color .2s, transform .3s var(--pop); }
        .field:focus-within .ico { color: var(--terracotta); transform: translateY(-50%) scale(1.15); }
        .field-control { width: 100%; height: 48px; padding: 0 40px 0 42px; border: 1px solid var(--border); border-radius: 11px; outline: none; background: var(--field); color: var(--text); font-family: inherit; font-size: 14px; caret-color: var(--terracotta); box-shadow: inset 0 1px 2px rgba(43, 33, 27, .04); transition: border-color .2s, box-shadow .25s, transform .2s; }
        .field-control::placeholder { color: #8F8379; transition: opacity .2s, transform .2s; }
        .field-control:focus::placeholder { opacity: 0; transform: translateX(8px); }
        .field-control:hover { border-color: #D8C6B8; }
        .field-control:focus { border-color: var(--terracotta); box-shadow: 0 0 0 4px rgba(200, 107, 69, .12); transform: translateY(-1px); }
        .field-control:-webkit-autofill { -webkit-box-shadow: 0 0 0 40px var(--field) inset; -webkit-text-fill-color: var(--text); }
        .field-control.is-invalid, .field-control[aria-invalid="true"] { border-color: var(--danger); box-shadow: 0 0 0 3px rgba(192, 57, 43, .08); animation: shake .4s; }
        @keyframes shake { 20%, 60% { transform: translateX(-5px); } 40%, 80% { transform: translateX(5px); } }
        .ok-ico { position: absolute; right: 13px; top: 50%; width: 16px; height: 16px; color: var(--ok); opacity: 0; transform: translateY(-50%) scale(.4); transition: opacity .2s, transform .3s var(--pop); pointer-events: none; }
        .field-control:not(:placeholder-shown):valid ~ .ok-ico { opacity: 1; transform: translateY(-50%) scale(1); }
        .password-wrap .field-control { padding-right: 52px; }
        .field-error { display: block; max-height: 0; opacity: 0; overflow: hidden; margin: 0; color: var(--danger); font-size: 12px; line-height: 1.4; transition: max-height .3s var(--ease), opacity .3s, margin .3s; }
        .field-error.show { max-height: 44px; opacity: 1; margin-top: 6px; }
        .password-toggle { position: absolute; top: 0; bottom: 0; right: 3px; width: 42px; display: grid; place-items: center; border: 0; background: transparent; color: var(--terracotta-dark); font-size: 17px; cursor: pointer; border-radius: 10px; transition: color .2s, transform .2s; }
        .password-toggle:hover { color: var(--heading); }
        .password-toggle:active { transform: scale(.9); }
        .password-toggle .eye-off, .password-toggle[aria-pressed="true"] .eye { display: none; }
        .password-toggle[aria-pressed="true"] .eye-off { display: block; }
        .caps { color: var(--danger); font-size: 11px; font-weight: 700; }
        a.ulink { position: relative; color: var(--terracotta-dark); font-size: 12px; font-weight: 700; text-decoration: none; }
        a.ulink::after { content: ""; position: absolute; left: 0; bottom: -2px; width: 100%; height: 1.5px; background: currentColor; transform: scaleX(0); transform-origin: left; transition: transform .3s var(--ease); }
        a.ulink:hover::after, a.ulink:focus-visible::after { transform: scaleX(1); }
        a.ulink:focus-visible { outline: 2px solid var(--terracotta); outline-offset: 3px; border-radius: 4px; }

        /* REMEMBER ME */
        .remember { display: flex; align-items: center; gap: 10px; margin: 2px 0 20px; }
        .remember input { appearance: none; -webkit-appearance: none; width: 18px; height: 18px; flex-shrink: 0; display: grid; place-content: center; border: 1.5px solid #BBAEA3; border-radius: 5px; background: var(--field); cursor: pointer; transition: background .2s, border-color .2s; }
        .remember input::after { content: ""; width: 9px; height: 5px; margin-top: -2px; border: 2px solid #fff; border-top: 0; border-right: 0; transform: rotate(-45deg) scale(0); transition: transform .2s .05s var(--pop); }
        .remember input:checked { background: var(--terracotta); border-color: var(--terracotta); animation: pop .3s var(--pop); }
        .remember input:checked::after { transform: rotate(-45deg) scale(1); }
        .remember input:focus-visible { outline: 2px solid var(--terracotta); outline-offset: 2px; }
        @keyframes pop { 50% { transform: scale(1.25); } }
        .remember label { color: var(--muted); font-size: 13px; cursor: pointer; }

        /* SUBMIT */
        .register-button { position: relative; overflow: hidden; width: 100%; min-height: 50px; padding: 12px 18px; display: flex; align-items: center; justify-content: center; gap: 10px; border: 0; border-radius: 11px; background: linear-gradient(120deg, var(--terracotta), var(--terracotta-dark), var(--terracotta)) 0 0 / 200% 100%; color: #fff; font-family: inherit; font-size: 14px; font-weight: 700; cursor: pointer; translate: var(--tx, 0) var(--ty, 0); transition: background-position .5s var(--ease), transform .2s, box-shadow .3s, translate .25s var(--ease); }
        .register-button::before { content: ""; position: absolute; top: 0; left: -60%; width: 40%; height: 100%; background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .35), transparent); transform: skewX(-20deg); }
        .register-button .arrow { display: inline-block; transition: transform .3s var(--ease); }
        .register-button:active { transform: scale(.98); }
        .register-button .spin { display: none; width: 16px; height: 16px; border: 2px solid rgba(255, 255, 255, .4); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
        .register-button.loading { pointer-events: none; opacity: .9; }
        .register-button.loading .spin { display: inline-block; }
        .register-button.loading .arrow { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .register-button.ready { opacity: 1; animation: glow 2s ease-in-out infinite; }
        .register-button.ready::before { animation: shineLoop 2.6s ease-in-out infinite; }
        .register-button.ready .arrow { animation: nudge 1.2s ease-in-out infinite; }
        @keyframes glow { 50% { box-shadow: 0 0 0 7px rgba(200, 107, 69, .18), 0 10px 26px rgba(200, 107, 69, .35); } }
        @keyframes nudge { 50% { transform: translateX(5px); } }
        @keyframes shineLoop { 0% { left: -60%; } 60%, 100% { left: 130%; } }
        .register-button.is-done { opacity: 1; background: var(--ok); animation: none; }
        .ripple { position: absolute; width: 10px; height: 10px; margin: -5px; border-radius: 50%; background: rgba(255, 255, 255, .45); animation: ripple .6s ease-out forwards; pointer-events: none; }
        @keyframes ripple { to { transform: scale(30); opacity: 0; } }
        @media (hover: hover) {
            .register-button:hover { background-position: 100% 0; box-shadow: 0 10px 24px rgba(200, 107, 69, .28); }
            .register-button:hover::before { left: 130%; transition: left .7s var(--ease); }
            .register-button:hover .arrow { transform: translateX(5px); }
        }
        .secure-note { display: flex; align-items: center; justify-content: center; gap: 6px; margin: 10px 0 0; color: var(--muted); font-size: 12px; }
        .login-footer { margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--border); text-align: center; color: var(--muted); font-size: 13px; }
        .login-footer a { margin-left: 4px; font-size: 13px; }

        /* TOAST */
        .toast { position: fixed; top: max(16px, env(safe-area-inset-top)); right: 16px; z-index: 60; width: min(360px, calc(100vw - 32px)); overflow: hidden; padding: 14px 40px 16px 16px; border: 1px solid #E9B9AE; border-left: 4px solid var(--danger); border-radius: 12px; background: #FFF0EC; color: #7E2F24; font-size: 13px; box-shadow: 0 14px 36px rgba(0, 0, 0, .15); animation: toastIn .5s var(--pop) both; transition: opacity .3s, transform .3s; outline: none; }
        .toast.out { opacity: 0; transform: translateY(-10px); pointer-events: none; }
        .toast strong { display: flex; align-items: center; gap: 7px; margin-bottom: 6px; }
        .toast strong svg { animation: dot 1.6s ease-in-out infinite; }
        .toast ul { margin: 0; padding-left: 18px; list-style: disc; }
        .toast button { position: absolute; top: 8px; right: 8px; width: 28px; height: 28px; border: 0; border-radius: 8px; background: transparent; color: inherit; font-size: 18px; cursor: pointer; }
        .toast .bar { position: absolute; left: 0; bottom: 0; height: 3px; width: 100%; background: var(--danger); transform-origin: left; animation: drain 9s linear forwards; }
        @keyframes toastIn { from { opacity: 0; transform: translateX(40px); } }
        @keyframes drain { to { transform: scaleX(0); } }

        /* SUCCESS OVERLAY */
        .success { position: fixed; inset: 0; z-index: 90; display: grid; place-items: center; padding: 16px; background: rgba(21, 16, 16, .6); backdrop-filter: blur(10px); opacity: 0; visibility: hidden; transition: opacity .4s, visibility .4s; }
        .success.show { opacity: 1; visibility: visible; }
        .success-card { position: relative; width: min(380px, 100%); padding: 34px 28px 26px; border: 1px solid var(--border); border-radius: 24px; background: var(--cream); text-align: center; box-shadow: 0 30px 90px rgba(0, 0, 0, .35); opacity: 0; transform: translateY(24px) scale(.85); }
        .success.show .success-card { animation: cardIn .7s var(--pop) .1s forwards; }
        @keyframes cardIn { to { opacity: 1; transform: none; } }
        .check-wrap { position: relative; width: 96px; height: 96px; margin: 0 auto 18px; color: var(--ok); }
        .check-wrap::before, .check-wrap::after { content: ""; position: absolute; inset: 0; border-radius: 50%; border: 2px solid currentColor; opacity: 0; }
        .success.show .check-wrap::before { animation: pulseRing 1.6s ease-out .9s infinite; }
        .success.show .check-wrap::after { animation: pulseRing 1.6s ease-out 1.5s infinite; }
        @keyframes pulseRing { from { opacity: .5; transform: scale(1); } to { opacity: 0; transform: scale(1.7); } }
        .check-wrap svg { position: relative; width: 100%; height: 100%; overflow: visible; }
        .c-bg { fill: rgba(46, 139, 87, .12); transform-box: fill-box; transform-origin: center; transform: scale(0); }
        .success.show .c-bg { animation: pop2 .5s var(--pop) .2s forwards; }
        @keyframes pop2 { to { transform: scale(1); } }
        .c-ring { fill: none; stroke: currentColor; stroke-width: 4; stroke-linecap: round; stroke-dasharray: 289; stroke-dashoffset: 289; transform-box: fill-box; transform-origin: center; transform: rotate(-90deg); }
        .success.show .c-ring { animation: dash .8s var(--ease) .3s forwards; }
        .c-tick { fill: none; stroke: currentColor; stroke-width: 6; stroke-linecap: round; stroke-linejoin: round; stroke-dasharray: 60; stroke-dashoffset: 60; }
        .success.show .c-tick { animation: dash .5s var(--ease) 1s forwards; }
        @keyframes dash { to { stroke-dashoffset: 0; } }
        .success-card h3 { margin: 0 0 6px; color: var(--heading); font-family: Georgia, serif; font-size: 27px; letter-spacing: -.5px; opacity: 0; }
        .success-card p { margin: 0 0 18px; color: var(--muted); font-size: 14px; opacity: 0; }
        .success.show h3 { animation: rise .6s var(--ease) 1.1s forwards; }
        .success.show .success-card p { animation: fadeIn .6s ease 1.3s forwards; }
        @keyframes fadeIn { to { opacity: 1; } }
        .s-bar { height: 4px; margin-bottom: 14px; overflow: hidden; border-radius: 4px; background: var(--border); }
        .s-bar i { display: block; width: 100%; height: 100%; transform: scaleX(0); transform-origin: left; background: linear-gradient(90deg, var(--terracotta), #E9A17E); }
        .success.show .s-bar i { animation: fill var(--wait, 2600ms) linear forwards; }
        @keyframes fill { to { transform: scaleX(1); } }
        .confetti { position: fixed; left: 50%; top: 42%; z-index: 91; pointer-events: none; }
        .confetti i { position: absolute; display: block; border-radius: 2px; opacity: 0; animation: burst 1.9s cubic-bezier(.15, .7, .3, 1) forwards; }
        @keyframes burst { 0% { opacity: 1; transform: translate(0, 0) rotate(0); } 70% { opacity: 1; } 100% { opacity: 0; transform: translate(var(--x), calc(var(--y) + 260px)) rotate(var(--r)); } }

        /* SHORT SCREENS / TABLET / MOBILE */
        @media (max-height: 740px) and (min-width: 851px) {
            .aside-content p, .form-heading p { display: none; }
            .login-aside { padding: 28px 32px; }
            .login-main { padding-top: 24px; padding-bottom: 20px; }
            .field-group { margin-bottom: 12px; }
            .field-control { height: 44px; }
            .benefit { padding: 6px 10px; }
        }
        @media (max-width: 850px) {
            .login-shell { max-width: 540px; max-height: none; grid-template-columns: 1fr; }
            .login-aside { padding: 18px 22px; flex-direction: row; align-items: center; }
            .login-aside::before, .login-aside::after, .aside-content, .benefits, .aside-footer { display: none; }
            .brand { display: flex; align-items: center; gap: 14px; }
            .brand-icon { width: 44px; height: 44px; margin: 0; font-size: 22px; }
            .brand-name { font-size: 24px; }
            .brand-subtitle { margin-top: 3px; font-size: 10px; }
            .login-main { padding: 30px 24px; overflow: visible; }
            .field-control { font-size: 16px; }
        }
        @media (max-width: 480px) {
            .login-page { padding: 10px; }
            .login-shell { border-radius: 18px; }
            .login-main { padding: 26px 18px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition-duration: .01ms !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; }
            .success *, .success *::before, .success *::after { animation-delay: 0s !important; }
            .login-shell::before, .blob { animation: none; }
            .reveal { opacity: 1; }
        }
    </style>

    <a class="skip" href="#email">Skip to form</a>

    {{-- SUCCESS OVERLAY --}}
    <div class="success" id="success" role="status" aria-hidden="true"
         data-home="{{ url(config('fortify.home', '/')) }}"
         data-2fa="{{ Route::has('two-factor.login') ? route('two-factor.login') : '' }}">
        <div class="confetti" id="confetti" aria-hidden="true"></div>
        <div class="success-card">
            <div class="check-wrap" aria-hidden="true">
                <svg viewBox="0 0 100 100">
                    <circle class="c-bg" cx="50" cy="50" r="46"/>
                    <circle class="c-ring" cx="50" cy="50" r="46"/>
                    <path class="c-tick" d="M30 52l13 13 27-29"/>
                </svg>
            </div>
            <h3 id="successTitle">Welcome back!</h3>
            <p id="successText">You're signed in. Taking you in…</p>
            <div class="s-bar" aria-hidden="true"><i></i></div>
            <a class="ulink" id="successLink" href="{{ url(config('fortify.home', '/')) }}">Continue now</a>
        </div>
    </div>

    <div class="login-page {{ $errors->any() ? 'has-errors' : '' }}">
        <div class="blob b1" aria-hidden="true"></div>
        <div class="blob b2" aria-hidden="true"></div>

        <div class="login-shell">

            {{-- LEFT BRANDING PANEL --}}
            <aside class="login-aside">
                <div class="spot" aria-hidden="true"></div>
                <div class="grain" aria-hidden="true"></div>
                <div class="sparkles" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span></div>

                <div class="brand reveal" style="--i:0">
                    <div class="brand-icon" aria-hidden="true">✿</div>
                    <div>
                        <div class="brand-name">Sweet Crumbs</div>
                        <div class="brand-subtitle">Bakery &amp; Patisserie</div>
                    </div>
                </div>

                <div class="aside-content">
                    <span class="eyebrow reveal" style="--i:1">Welcome back</span>
                    <h1 class="reveal" style="--i:2">Your favourites are<br><span class="grad">fresh out of the oven.</span></h1>
                    <p class="reveal" style="--i:3">Sign in to track your orders, reorder the treats you love and enjoy member-only offers.</p>
                </div>

                <div class="benefits">
                    <div class="benefit reveal" style="--i:4">
                        <div class="benefit-icon" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.7zM3.3 7 12 12l8.7-5M12 22V12"/></svg></div>
                        <span>Track your orders live</span>
                    </div>
                    <div class="benefit reveal" style="--i:5">
                        <div class="benefit-icon" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8z"/></svg></div>
                        <span>Save your favourites</span>
                    </div>
                    <div class="benefit reveal" style="--i:6">
                        <div class="benefit-icon" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg></div>
                        <span>Quick &amp; secure checkout</span>
                    </div>
                </div>

                <div class="aside-footer reveal" style="--i:7">MADE FRESH. SERVED WITH LOVE.</div>
            </aside>

            {{-- LOGIN PANEL --}}
            <main class="login-main" id="main">

                <div class="form-heading reveal" style="--i:1">
                    <div class="head-row">
                        <span class="eyebrow">Member sign in</span>
                        <button type="button" class="theme-btn" id="themeBtn" aria-label="Toggle dark mode" title="Light / dark">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 1 0 18z" fill="currentColor"/></svg>
                        </button>
                    </div>
                    <h2>Sign in to your account</h2>
                    <p>Enter your details to continue.</p>
                </div>

                {{-- STATUS MESSAGE (e.g. password reset link / password changed) --}}
                @session('status')
                    <div class="status-box" role="status">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                        {{ $value }}
                    </div>
                @endsession

                {{-- SERVER VALIDATION ERRORS AS TOAST --}}
                @if ($errors->any())
                    <div class="toast" id="toast" role="alert" tabindex="-1">
                        <strong>
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0zM12 9v4M12 17h.01"/></svg>
                            Please check the following:
                        </strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" id="toastClose" aria-label="Dismiss">×</button>
                        <span class="bar" aria-hidden="true"></span>
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    {{-- EMAIL --}}
                    <div class="field-group reveal" style="--i:2">
                        <div class="label-row">
                            <label class="field-label" for="email">Email Address <span class="sr-only">(required)</span></label>
                        </div>
                        <div class="field">
                            <svg class="i ico" viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
                            <input id="email" class="field-control @error('email') is-invalid @enderror" type="email" inputmode="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="username" autocapitalize="off" spellcheck="false" enterkeyhint="next" maxlength="255" required autofocus @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                            <svg class="i ok-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        @error('email')<small class="field-error show" id="email-error">{{ $message }}</small>@enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="field-group reveal" style="--i:3">
                        <div class="label-row">
                            <label class="field-label" for="password">Password <span class="sr-only">(required)</span></label>
                            <span class="caps" id="caps" hidden>Caps Lock on</span>
                            @if (Route::has('password.request'))
                                <a class="ulink" href="{{ route('password.request') }}">Forgot password?</a>
                            @endif
                        </div>
                        <div class="field password-wrap">
                            <svg class="i ico" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input id="password" class="field-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" enterkeyhint="go" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                            <button type="button" class="password-toggle" aria-controls="password" aria-label="Show password" aria-pressed="false" title="Show / hide password">
                                <svg class="i eye" viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="i eye-off" viewBox="0 0 24 24"><path d="M17.9 17.9A10.9 10.9 0 0 1 12 19c-6.4 0-10-7-10-7a18 18 0 0 1 4.1-5M9.9 5.2A9.7 9.7 0 0 1 12 5c6.4 0 10 7 10 7a18 18 0 0 1-2.2 3.2M1 1l22 22"/></svg>
                            </button>
                        </div>
                        @error('password')<small class="field-error show" id="password-error">{{ $message }}</small>@enderror
                    </div>

                    {{-- REMEMBER ME --}}
                    <div class="remember reveal" style="--i:4">
                        <input type="checkbox" id="remember_me" name="remember">
                        <label for="remember_me">{{ __('Remember me') }}</label>
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit" class="register-button reveal" style="--i:5">
                        <span class="label">{{ __('Log in') }}</span>
                        <span class="arrow" aria-hidden="true">→</span>
                        <span class="spin" aria-hidden="true"></span>
                    </button>
                    <p class="secure-note reveal" style="--i:6">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Your connection is encrypted and secure.
                    </p>
                </form>

                @if (Route::has('register'))
                    <div class="login-footer reveal" style="--i:7">
                        New here?
                        <a class="ulink" href="{{ route('register') }}">Create an account</a>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const $ = (s) => document.querySelector(s);
            const $$ = (s) => Array.from(document.querySelectorAll(s));
            const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
            const form = $('#loginForm'), email = $('#email'), pw = $('#password'), btn = $('.register-button');
            const shell = $('.login-shell'), success = $('#success');
            const title = document.title;

            /* Keep revealed elements visible once their entrance ends */
            $$('.reveal').forEach(function (el) {
                el.addEventListener('animationend', function (e) {
                    if (e.target === el && e.animationName === 'rise') el.classList.add('revealed');
                });
            });

            /* Theme toggle (remembered) */
            const root = document.documentElement;
            try { const t = localStorage.getItem('reg_theme'); if (t) root.dataset.theme = t; } catch (e) {}
            $('#themeBtn').addEventListener('click', function () {
                const dark = root.dataset.theme ? root.dataset.theme === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches;
                const next = dark ? 'light' : 'dark';
                root.classList.add('theme-anim');
                root.dataset.theme = next;
                try { localStorage.setItem('reg_theme', next); } catch (e) {}
                setTimeout(() => root.classList.remove('theme-anim'), 500);
            });

            /* Ready state: glow when both fields look good */
            function update() {
                const ok = email.value.trim() !== '' && email.checkValidity() && pw.value !== '';
                btn.classList.toggle('ready', ok && !btn.classList.contains('loading') && !btn.classList.contains('is-done'));
            }
            form.addEventListener('input', update);
            update();

            /* Tidy email, clear inline errors while typing */
            email.addEventListener('blur', function () { email.value = email.value.trim().toLowerCase(); update(); });
            [email, pw].forEach(function (f) {
                f.addEventListener('input', function () {
                    const er = document.getElementById(f.id + '-error');
                    if (er) er.classList.remove('show');
                    f.classList.remove('is-invalid'); f.removeAttribute('aria-invalid');
                });
                f.addEventListener('animationend', () => f.classList.remove('is-invalid'));
            });

            /* Email domain suggestions */
            const dl = document.createElement('datalist');
            dl.id = 'emailList';
            document.body.appendChild(dl);
            email.setAttribute('list', 'emailList');
            email.addEventListener('input', function () {
                const v = email.value, at = v.indexOf('@');
                dl.textContent = '';
                if (at < 1) return;
                const local = v.slice(0, at), part = v.slice(at + 1).toLowerCase();
                ['gmail.com', 'outlook.com', 'yahoo.com', 'hotmail.com', 'icloud.com']
                    .filter((d) => d.startsWith(part) && d !== part)
                    .forEach(function (d) { const o = document.createElement('option'); o.value = local + '@' + d; dl.appendChild(o); });
            });

            /* Show / hide password (auto-hide) + caps lock */
            let hideTimer;
            const toggle = $('.password-toggle');
            function setShown(on) {
                pw.type = on ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', String(on));
                toggle.setAttribute('aria-label', on ? 'Hide password' : 'Show password');
                clearTimeout(hideTimer);
                if (on) hideTimer = setTimeout(() => setShown(false), 10000);
            }
            toggle.addEventListener('click', () => setShown(pw.type === 'password'));
            document.addEventListener('visibilitychange', function () {
                if (document.hidden) { setShown(false); document.title = 'Welcome back…'; } else { document.title = title; }
            });
            ['keydown', 'keyup'].forEach((ev) => pw.addEventListener(ev, function (e) {
                $('#caps').hidden = !(e.getModifierState && e.getModifierState('CapsLock'));
            }));
            pw.addEventListener('blur', () => { $('#caps').hidden = true; });

            /* Toast + inline errors */
            const warnIcon = '<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0zM12 9v4M12 17h.01"/></svg>';
            function showToast(list) {
                const old = $('#toast');
                if (old) old.remove();
                const t = document.createElement('div');
                t.id = 'toast'; t.className = 'toast'; t.setAttribute('role', 'alert'); t.tabIndex = -1;
                t.innerHTML = '<strong>' + warnIcon + ' Please check the following:</strong><ul></ul><button type="button" aria-label="Dismiss">×</button><span class="bar" aria-hidden="true"></span>';
                list.forEach(function (m) { const li = document.createElement('li'); li.textContent = m; t.querySelector('ul').appendChild(li); });
                $('.login-main').appendChild(t);
                const close = () => t.classList.add('out');
                t.querySelector('button').addEventListener('click', close);
                setTimeout(close, 9000);
                t.focus();
            }
            const serverToast = $('#toast');
            if (serverToast) {
                serverToast.focus();
                const close = () => serverToast.classList.add('out');
                $('#toastClose').addEventListener('click', close);
                setTimeout(close, 9000);
            }
            function setFieldError(key, msg) {
                let el = document.getElementById(key + '-error');
                if (!el) {
                    const f = document.getElementById(key), g = f && f.closest('.field-group');
                    if (!g) return;
                    el = document.createElement('small');
                    el.className = 'field-error'; el.id = key + '-error';
                    g.appendChild(el);
                }
                el.textContent = msg;
                el.classList.add('show');
            }
            function resetBtn() {
                btn.classList.remove('loading');
                btn.removeAttribute('aria-busy');
                btn.querySelector('.label').textContent = @json(__('Log in'));
                update();
            }

            /* Success celebration */
            function confetti() {
                if (reduce) return;
                const box = $('#confetti'), colors = ['#C86B45', '#E9A17E', '#FFF9F2', '#F2C14E', '#2E8B57', '#AD5634'];
                box.textContent = '';
                for (let i = 0; i < 70; i++) {
                    const p = document.createElement('i'), a = Math.random() * Math.PI * 2, d = 120 + Math.random() * 320;
                    p.style.setProperty('--x', (Math.cos(a) * d).toFixed(0) + 'px');
                    p.style.setProperty('--y', (Math.sin(a) * d - 120).toFixed(0) + 'px');
                    p.style.setProperty('--r', (Math.random() * 720 - 360).toFixed(0) + 'deg');
                    p.style.background = colors[i % colors.length];
                    p.style.animationDelay = (0.55 + Math.random() * 0.25).toFixed(2) + 's';
                    p.style.width = (6 + Math.random() * 6).toFixed(0) + 'px';
                    p.style.height = (8 + Math.random() * 8).toFixed(0) + 'px';
                    if (i % 3 === 0) p.style.borderRadius = '50%';
                    box.appendChild(p);
                }
            }
            function celebrate(dest, twoFactor) {
                const wait = reduce ? 900 : 2600;
                $('#successTitle').textContent = twoFactor ? 'Almost there!' : 'Welcome back!';
                $('#successText').textContent = twoFactor ? 'One more step to verify it’s you…' : 'You’re signed in. Taking you in…';
                $('#successLink').href = dest;
                btn.classList.remove('loading', 'ready');
                btn.classList.add('is-done');
                btn.querySelector('.label').textContent = 'Signed in!';
                shell.classList.add('done');
                success.style.setProperty('--wait', wait + 'ms');
                success.classList.add('show');
                success.setAttribute('aria-hidden', 'false');
                confetti();
                setTimeout(function () { window.location.href = dest; }, wait);
            }

            /* Submit via fetch (JSON), fall back to normal submit */
            function sendAjax() {
                const token = (form.querySelector('input[name="_token"]') || {}).value || '';
                fetch(form.action, {
                    method: 'POST', body: new FormData(form), credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token }
                }).then(async function (res) {
                    if (res.ok) {
                        const j = await res.json().catch(() => ({}));
                        if (j && j.two_factor && success.dataset['2fa']) { celebrate(success.dataset['2fa'], true); }
                        else { celebrate(success.dataset.home, false); }
                        return;
                    }
                    if (res.status === 422) {
                        const j = await res.json().catch(() => ({}));
                        const errs = j.errors || {}, msgs = [];
                        Object.keys(errs).forEach(function (k) {
                            msgs.push(errs[k][0]);
                            const f = document.getElementById(k);
                            if (f) { void f.offsetWidth; f.classList.add('is-invalid'); f.setAttribute('aria-invalid', 'true'); }
                            setFieldError(k, errs[k][0]);
                        });
                        showToast(msgs.length ? msgs : ['Please check your details and try again.']);
                        if (errs.email && email.checkValidity()) { pw.classList.add('is-invalid'); pw.value = ''; pw.focus(); }
                        else { const first = Object.keys(errs)[0], ff = first && document.getElementById(first); if (ff) ff.focus(); }
                    } else if (res.status === 419) {
                        showToast(['Your session expired. Please refresh the page and try again.']);
                    } else if (res.status === 429) {
                        showToast(['Too many attempts. Please wait a minute and try again.']);
                    } else {
                        showToast(['Something went wrong. Please try again.']);
                    }
                    if (navigator.vibrate) navigator.vibrate(60);
                    resetBtn();
                }).catch(function () { form.submit(); });
            }

            form.addEventListener('submit', function (event) {
                if (btn.classList.contains('loading') || btn.classList.contains('is-done')) { event.preventDefault(); return; }
                let first = null;
                [email, pw].forEach(function (f) {
                    f.classList.remove('is-invalid');
                    if (!f.checkValidity()) {
                        void f.offsetWidth;
                        f.classList.add('is-invalid'); f.setAttribute('aria-invalid', 'true');
                        first = first || f;
                    }
                });
                if (first) {
                    event.preventDefault();
                    first.focus();
                    if (navigator.vibrate) navigator.vibrate(60);
                    return;
                }
                btn.classList.add('loading');
                btn.classList.remove('ready');
                btn.setAttribute('aria-busy', 'true');
                btn.querySelector('.label').textContent = 'Signing in…';
                if (!window.fetch) return;
                event.preventDefault();
                sendAjax();
            });
            window.addEventListener('pageshow', function () { shell.classList.remove('leaving'); resetBtn(); });

            /* Button: ripple + magnetic; form side glow; left panel spotlight/parallax */
            btn.addEventListener('pointerdown', function (e) {
                if (reduce) return;
                const r = btn.getBoundingClientRect(), s = document.createElement('span');
                s.className = 'ripple';
                s.style.left = (e.clientX - r.left) + 'px';
                s.style.top = (e.clientY - r.top) + 'px';
                btn.appendChild(s);
                s.addEventListener('animationend', () => s.remove());
            });
            if (!reduce && matchMedia('(hover: hover)').matches) {
                btn.addEventListener('mousemove', function (e) {
                    const r = btn.getBoundingClientRect();
                    btn.style.setProperty('--tx', ((e.clientX - r.left - r.width / 2) * .06).toFixed(1) + 'px');
                    btn.style.setProperty('--ty', ((e.clientY - r.top - r.height / 2) * .15).toFixed(1) + 'px');
                });
                btn.addEventListener('mouseleave', function () { btn.style.setProperty('--tx', '0px'); btn.style.setProperty('--ty', '0px'); });
                const main = $('.login-main');
                main.addEventListener('mousemove', function (e) {
                    const r = main.getBoundingClientRect();
                    main.style.setProperty('--fx', (e.clientX - r.left) + 'px');
                    main.style.setProperty('--fy', (e.clientY - r.top) + 'px');
                });
                const aside = $('.login-aside');
                aside.addEventListener('mousemove', function (e) {
                    const r = aside.getBoundingClientRect(), x = e.clientX - r.left, y = e.clientY - r.top;
                    aside.style.setProperty('--mx', x + 'px');
                    aside.style.setProperty('--my', y + 'px');
                    aside.style.setProperty('--px', ((x / r.width - .5) * -24).toFixed(1));
                    aside.style.setProperty('--py', ((y / r.height - .5) * -24).toFixed(1));
                });
            }

            /* Rotating taglines */
            const ft = $('.aside-footer');
            if (ft && !reduce) {
                const lines = ['MADE FRESH. SERVED WITH LOVE.', 'BAKED DAILY. DELIVERED WARM.', 'SWEET MOMENTS START HERE.'];
                let k = 0;
                setInterval(function () {
                    ft.style.transition = 'opacity .4s';
                    ft.style.opacity = 0;
                    setTimeout(function () { k = (k + 1) % lines.length; ft.textContent = lines[k]; ft.style.opacity = 1; }, 400);
                }, 4500);
            }

            /* Smooth exit when leaving for Register / Forgot password */
            $$('.login-footer a, .label-row a').forEach(function (a) {
                a.addEventListener('click', function (e) {
                    if (e.metaKey || e.ctrlKey || e.shiftKey || reduce) return;
                    e.preventDefault();
                    shell.classList.add('leaving');
                    setTimeout(function () { window.location.href = a.href; }, 300);
                });
            });
        });
    </script>
</x-guest-layout>