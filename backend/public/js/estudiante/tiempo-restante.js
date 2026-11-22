/* El plazo del servidor prevalece; contador orientativo sin ampliar permisos. */
(() => {
 'use strict';
 const cfg = window.__FICHA_4TO || window.fichaThirdYear;
 if (!cfg?.expires_at) return;
 document.addEventListener('DOMContentLoaded', () => {
   const deadline = Date.parse(cfg.expires_at);
   if (!Number.isFinite(deadline)) return;
   const banner = document.querySelector('.case-banner,.page-heading,.case-status') || document.querySelector('main');
   if (!banner) return;
   const el=document.createElement('div');el.id='tiempoRestante';el.setAttribute('role','timer');el.setAttribute('aria-label','Tiempo restante para completar el caso');
   el.style.cssText='padding:7px 12px;border:1px solid #cbd5e1;border-radius:9px;background:#f8fafc;color:#172b48;font-size:14px;font-weight:650;margin:8px 0;display:inline-block';
   banner.append(el);
   const update=()=>{
      const remain=Math.max(0,Math.floor((deadline-Date.now())/1000));
      const hh=Math.floor(remain/3600),mm=Math.floor(remain%3600/60),ss=remain%60;
      el.textContent=remain ? `Tiempo restante: ${[hh,mm,ss].map(n=>String(n).padStart(2,'0')).join(':')}` : 'Tiempo agotado - esta ficha ya no admite cambios';
      el.style.color=remain===0?'#991b1b':remain<=300?'#9a3412':'#172b48';
      if(!remain)clearInterval(timer);
   };
   const timer=setInterval(update,1000);update();
 });
})();
