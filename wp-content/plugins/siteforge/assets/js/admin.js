/* SiteForge Admin JS */
(function($){
'use strict';

/* ─── SECTION LIBRARY ─────────────────────────────────────────────────── */
const SECTIONS = [
  {
    id:'hero', label:'Hero Banner', icon:'🏠', category:'Layout',
    fields:{ heading:'Welcome to Our Store', subheading:'Discover amazing products', btn_text:'Shop Now', btn_url:'#', bg_color:'#1a1a2e', text_color:'#ffffff', overlay:true },
    render(f){ return `<div class="sf-sec-hero" style="background:${f.bg_color};color:${f.text_color}"><div class="sf-hero-inner"><h1>${f.heading}</h1><p>${f.subheading}</p><a href="${f.btn_url}" class="sf-hero-btn">${f.btn_text}</a></div></div>`; }
  },
  {
    id:'products', label:'Products Grid', icon:'🛍️', category:'Shop',
    fields:{ title:'Our Products', cols:3, items: JSON.stringify([{img:'https://via.placeholder.com/300x300/667eea/fff?text=Product+1',name:'Product 1',price:'$29.99',badge:'New'},{img:'https://via.placeholder.com/300x300/764ba2/fff?text=Product+2',name:'Product 2',price:'$49.99',badge:''},{img:'https://via.placeholder.com/300x300/f093fb/fff?text=Product+3',name:'Product 3',price:'$19.99',badge:'Sale'}]) },
    render(f){
      const items = safeJSON(f.items,[]);
      const cards = items.map(i=>`<div class="sf-product-card">${i.badge?`<span class="sf-badge">${i.badge}</span>`:''}<img src="${i.img}" alt="${i.name}"><div class="sf-product-info"><h3>${i.name}</h3><span class="sf-price">${i.price}</span><button class="sf-add-cart">Add to Cart</button></div></div>`).join('');
      return `<div class="sf-sec-products"><h2 class="sf-sec-title">${f.title}</h2><div class="sf-products-grid" style="--cols:${f.cols}">${cards}</div></div>`;
    }
  },
  {
    id:'food_menu', label:'Food Menu', icon:'🍽️', category:'Food',
    fields:{ title:'Our Menu', accent:'#e74c3c', items: JSON.stringify([{name:'Margherita Pizza',desc:'Fresh tomatoes, mozzarella, basil',price:'$14.99',img:'https://via.placeholder.com/80x80/e74c3c/fff?text=🍕'},{name:'Caesar Salad',desc:'Romaine, croutons, parmesan',price:'$9.99',img:'https://via.placeholder.com/80x80/27ae60/fff?text=🥗'},{name:'Grilled Salmon',desc:'Atlantic salmon, lemon butter',price:'$24.99',img:'https://via.placeholder.com/80x80/2980b9/fff?text=🐟'}]) },
    render(f){
      const items = safeJSON(f.items,[]);
      const rows = items.map(i=>`<div class="sf-menu-item"><img src="${i.img}" alt="${i.name}"><div class="sf-menu-info"><h3>${i.name}</h3><p>${i.desc}</p></div><span class="sf-menu-price" style="color:${f.accent}">${i.price}</span></div>`).join('');
      return `<div class="sf-sec-food"><h2 class="sf-sec-title">${f.title}</h2><div class="sf-menu-list">${rows}</div></div>`;
    }
  },
  {
    id:'testimonials', label:'Testimonials', icon:'⭐', category:'Social',
    fields:{ title:'What Customers Say', bg:'#f8f9fa', items: JSON.stringify([{name:'Sarah M.',role:'Regular Customer',text:'Absolutely love this place! Best quality and service.',stars:5},{name:'James K.',role:'Food Blogger',text:'Incredible experience every single time.',stars:5},{name:'Lisa R.',role:'Local Guide',text:'Highly recommend to everyone in the area.',stars:4}]) },
    render(f){
      const items = safeJSON(f.items,[]);
      const cards = items.map(i=>`<div class="sf-testimonial-card"><div class="sf-stars">${'★'.repeat(i.stars)}${'☆'.repeat(5-i.stars)}</div><p>"${i.text}"</p><div class="sf-reviewer"><strong>${i.name}</strong><span>${i.role}</span></div></div>`).join('');
      return `<div class="sf-sec-testimonials" style="background:${f.bg}"><h2 class="sf-sec-title">${f.title}</h2><div class="sf-testimonials-grid">${cards}</div></div>`;
    }
  },
  {
    id:'cta', label:'Call to Action', icon:'📣', category:'Layout',
    fields:{ heading:'Ready to Get Started?', subtext:'Join thousands of happy customers today.', btn_text:'Get Started', btn_url:'#', bg:'#667eea', text_color:'#ffffff' },
    render(f){ return `<div class="sf-sec-cta" style="background:${f.bg};color:${f.text_color}"><h2>${f.heading}</h2><p>${f.subtext}</p><a href="${f.btn_url}" class="sf-cta-btn">${f.btn_text}</a></div>`; }
  },
  {
    id:'gallery', label:'Photo Gallery', icon:'🖼️', category:'Media',
    fields:{ title:'Gallery', cols:3, images: JSON.stringify(['https://via.placeholder.com/400x300/667eea/fff?text=Photo+1','https://via.placeholder.com/400x300/764ba2/fff?text=Photo+2','https://via.placeholder.com/400x300/f093fb/fff?text=Photo+3','https://via.placeholder.com/400x300/4facfe/fff?text=Photo+4','https://via.placeholder.com/400x300/43e97b/fff?text=Photo+5','https://via.placeholder.com/400x300/fa709a/fff?text=Photo+6']) },
    render(f){
      const imgs = safeJSON(f.images,[]);
      const items = imgs.map(src=>`<div class="sf-gallery-item"><img src="${src}" alt="Gallery"></div>`).join('');
      return `<div class="sf-sec-gallery"><h2 class="sf-sec-title">${f.title}</h2><div class="sf-gallery-grid" style="--cols:${f.cols}">${items}</div></div>`;
    }
  },
  {
    id:'contact', label:'Contact Form', icon:'📬', category:'Forms',
    fields:{ title:'Get In Touch', subtitle:'We\'d love to hear from you', accent:'#667eea', show_phone:true, show_address:true, phone:'+1 (555) 000-0000', address:'123 Main St, City, State' },
    render(f){ return `<div class="sf-sec-contact"><div class="sf-contact-inner"><div class="sf-contact-info"><h2>${f.title}</h2><p>${f.subtitle}</p>${f.show_phone?`<p>📞 ${f.phone}</p>`:''} ${f.show_address?`<p>📍 ${f.address}</p>`:''}</div><form class="sf-contact-form" onsubmit="return false"><input type="text" placeholder="Your Name"><input type="email" placeholder="Email Address"><textarea placeholder="Your Message" rows="4"></textarea><button type="submit" style="background:${f.accent}">Send Message</button></form></div></div>`; }
  },
  {
    id:'features', label:'Features / Services', icon:'✨', category:'Layout',
    fields:{ title:'Why Choose Us', items: JSON.stringify([{icon:'🚀',title:'Fast Delivery',desc:'Same day delivery available'},{icon:'💎',title:'Premium Quality',desc:'Only the best products'},{icon:'🛡️',title:'Secure Payment',desc:'100% safe transactions'},{icon:'🎁',title:'Free Returns',desc:'30-day return policy'}]) },
    render(f){
      const items = safeJSON(f.items,[]);
      const cards = items.map(i=>`<div class="sf-feature-card"><div class="sf-feature-icon">${i.icon}</div><h3>${i.title}</h3><p>${i.desc}</p></div>`).join('');
      return `<div class="sf-sec-features"><h2 class="sf-sec-title">${f.title}</h2><div class="sf-features-grid">${cards}</div></div>`;
    }
  },
  {
    id:'text_block', label:'Text Block', icon:'📝', category:'Layout',
    fields:{ heading:'About Us', content:'Tell your story here. Share what makes your business unique and why customers should choose you.', align:'center', max_width:'800px' },
    render(f){ return `<div class="sf-sec-text" style="text-align:${f.align}"><div style="max-width:${f.max_width};margin:0 auto"><h2>${f.heading}</h2><p>${f.content}</p></div></div>`; }
  },
  {
    id:'pricing', label:'Pricing Table', icon:'💰', category:'Shop',
    fields:{ title:'Simple Pricing', plans: JSON.stringify([{name:'Starter',price:'$9',period:'/mo',features:['5 Products','Basic Analytics','Email Support'],highlight:false},{name:'Pro',price:'$29',period:'/mo',features:['Unlimited Products','Advanced Analytics','Priority Support','Custom Domain'],highlight:true},{name:'Enterprise',price:'$99',period:'/mo',features:['Everything in Pro','Dedicated Manager','SLA Guarantee','API Access'],highlight:false}]) },
    render(f){
      const plans = safeJSON(f.plans,[]);
      const cards = plans.map(p=>`<div class="sf-pricing-card${p.highlight?' sf-pricing-highlight':''}"><h3>${p.name}</h3><div class="sf-pricing-price">${p.price}<span>${p.period}</span></div><ul>${p.features.map(ft=>`<li>✓ ${ft}</li>`).join('')}</ul><button class="sf-pricing-btn">Get Started</button></div>`).join('');
      return `<div class="sf-sec-pricing"><h2 class="sf-sec-title">${f.title}</h2><div class="sf-pricing-grid">${cards}</div></div>`;
    }
  },
  {
    id:'map_embed', label:'Map / Location', icon:'📍', category:'Info',
    fields:{ title:'Find Us', embed_url:'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1!2d-73.98!3d40.75!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQ1JzAwLjAiTiA3M8KwNTgnNDguMCJX!5e0!3m2!1sen!2sus!4v1234567890', height:400 },
    render(f){ return `<div class="sf-sec-map"><h2 class="sf-sec-title">${f.title}</h2><iframe src="${f.embed_url}" width="100%" height="${f.height}" style="border:0;border-radius:12px" allowfullscreen loading="lazy"></iframe></div>`; }
  },
  {
    id:'social_links', label:'Social Links', icon:'🔗', category:'Social',
    fields:{ title:'Follow Us', bg:'#1a1a2e', text_color:'#fff', links: JSON.stringify([{platform:'Instagram',url:'#',icon:'📸'},{platform:'Facebook',url:'#',icon:'👥'},{platform:'Twitter',url:'#',icon:'🐦'},{platform:'TikTok',url:'#',icon:'🎵'}]) },
    render(f){
      const links = safeJSON(f.links,[]);
      const btns = links.map(l=>`<a href="${l.url}" class="sf-social-btn">${l.icon} ${l.platform}</a>`).join('');
      return `<div class="sf-sec-social" style="background:${f.bg};color:${f.text_color}"><h2>${f.title}</h2><div class="sf-social-links">${btns}</div></div>`;
    }
  }
];

const CATEGORIES = [...new Set(SECTIONS.map(s=>s.category))];

/* ─── HELPERS ─────────────────────────────────────────────────────────── */
function safeJSON(str, fallback){ try{ return typeof str==='string'?JSON.parse(str):str; }catch(e){ return fallback; } }
function uid(){ return 'sf_'+Math.random().toString(36).slice(2,9); }
function api(path, method='GET', body=null){
  return $.ajax({ url: SF.api+path, method, headers:{'X-WP-Nonce':SF.nonce}, contentType:'application/json', data: body?JSON.stringify(body):null });
}
function toast(msg, type='success'){
  const t=$(`<div class="sf-toast sf-toast-${type}">${msg}</div>`);
  $('body').append(t);
  setTimeout(()=>t.addClass('sf-toast-show'),10);
  setTimeout(()=>{ t.removeClass('sf-toast-show'); setTimeout(()=>t.remove(),300); },2500);
}

/* ─── STATE ───────────────────────────────────────────────────────────── */
let state = { pageId:0, title:'Untitled Page', status:'draft', sections:[], selected:null, settings:{ theme:'light', font:'Inter' } };

/* ─── DASHBOARD ───────────────────────────────────────────────────────── */
function initDashboard(){
  if(!$('#sf-dashboard').length) return;
  loadPages();
  $('#sf-new-page').on('click', createPage);
}

function loadPages(){
  api('/pages').done(pages=>{
    const grid = $('#sf-pages-grid').empty();
    if(!pages.length){
      grid.html('<div class="sf-empty-state"><div class="sf-empty-icon">📄</div><p>No pages yet. Create your first page!</p></div>');
      return;
    }
    pages.forEach(p=>{
      grid.append(`
        <div class="sf-page-card" data-id="${p.id}">
          <div class="sf-page-thumb">${p.thumbnail||'<div class="sf-thumb-placeholder">📄</div>'}</div>
          <div class="sf-page-meta">
            <h3>${p.title}</h3>
            <span class="sf-status sf-status-${p.status}">${p.status}</span>
          </div>
          <div class="sf-page-actions">
            <a href="${SF.admin}?page=siteforge-editor&page_id=${p.id}" class="sf-btn sf-btn-sm">Edit</a>
            <a href="${p.url}" target="_blank" class="sf-btn sf-btn-sm sf-btn-outline">View</a>
            <button class="sf-btn sf-btn-sm sf-btn-danger sf-delete-page" data-id="${p.id}">Delete</button>
          </div>
        </div>`);
    });
  }).fail(()=>{ $('#sf-pages-grid').html('<div class="sf-error">Failed to load pages.</div>'); });
}

function createPage(){
  api('/pages','POST',{ title:'New Page', status:'draft', sections:[], settings:{} }).done(p=>{
    window.location = SF.admin+'?page=siteforge-editor&page_id='+p.id;
  }).fail(()=>toast('Failed to create page','error'));
}

$(document).on('click','.sf-delete-page',function(e){
  e.stopPropagation();
  if(!confirm('Delete this page?')) return;
  const id=$(this).data('id');
  api('/pages/'+id,'DELETE').done(()=>{ toast('Page deleted'); loadPages(); }).fail(()=>toast('Delete failed','error'));
});

/* ─── EDITOR ──────────────────────────────────────────────────────────── */
function initEditor(){
  const wrap = $('#sf-editor');
  if(!wrap.length) return;
  state.pageId = parseInt(wrap.data('page'))||0;
  if(state.pageId) loadPage();
  buildSectionList();
  bindEditorEvents();
  initDragDrop();
}

function loadPage(){
  api('/pages/'+state.pageId).done(p=>{
    state.title   = p.title;
    state.status  = p.status;
    state.sections= p.sections||[];
    state.settings= p.settings||{ theme:'light', font:'Inter' };
    $('#sf-page-title').text(state.title);
    $('#sf-page-status').val(state.status);
    $('#sf-theme-select').val(state.settings.theme||'light');
    $('#sf-font-select').val(state.settings.font||'Inter');
    renderCanvas();
  }).fail(()=>toast('Failed to load page','error'));
}

function buildSectionList(filter=''){
  const list = $('#sf-section-list').empty();
  const q = filter.toLowerCase();
  CATEGORIES.forEach(cat=>{
    const items = SECTIONS.filter(s=>s.category===cat && (!q || s.label.toLowerCase().includes(q)));
    if(!items.length) return;
    list.append(`<div class="sf-cat-label">${cat}</div>`);
    items.forEach(s=>{
      list.append(`<div class="sf-section-item" data-id="${s.id}" draggable="true"><span class="sf-sec-icon">${s.icon}</span><span>${s.label}</span></div>`);
    });
  });
}

function renderCanvas(){
  const canvas = $('#sf-canvas');
  const empty  = $('#sf-canvas-empty');
  canvas.find('.sf-canvas-section').remove();
  if(!state.sections.length){ empty.show(); return; }
  empty.hide();
  state.sections.forEach(sec=>{ canvas.append(buildSectionEl(sec)); });
  applyPageSettings();
}

function buildSectionEl(sec){
  const def = SECTIONS.find(s=>s.id===sec.type);
  if(!def) return '';
  const html = def.render({...def.fields,...sec.fields});
  return $(`
    <div class="sf-canvas-section${state.selected===sec.uid?' sf-selected':''}" data-uid="${sec.uid}">
      <div class="sf-section-controls">
        <button class="sf-ctrl sf-ctrl-up" title="Move Up">↑</button>
        <button class="sf-ctrl sf-ctrl-down" title="Move Down">↓</button>
        <button class="sf-ctrl sf-ctrl-dup" title="Duplicate">⧉</button>
        <button class="sf-ctrl sf-ctrl-del" title="Delete">✕</button>
      </div>
      <div class="sf-section-preview">${html}</div>
    </div>`);
}

function addSection(typeId){
  const def = SECTIONS.find(s=>s.id===typeId);
  if(!def) return;
  const sec = { uid:uid(), type:typeId, fields:{...def.fields} };
  state.sections.push(sec);
  $('#sf-canvas-empty').hide();
  $('#sf-canvas').append(buildSectionEl(sec));
  selectSection(sec.uid);
  toast(`${def.label} added`);
}

function selectSection(uid){
  state.selected = uid;
  $('.sf-canvas-section').removeClass('sf-selected');
  $(`.sf-canvas-section[data-uid="${uid}"]`).addClass('sf-selected');
  renderPropsPanel(uid);
}

function renderPropsPanel(uid){
  const sec = state.sections.find(s=>s.uid===uid);
  if(!sec){ $('#sf-props-panel').html('<div class="sf-props-empty">Select a section to edit</div>'); return; }
  const def = SECTIONS.find(s=>s.id===sec.type);
  const panel = $('#sf-props-panel').empty();
  panel.append(`<div class="sf-props-header"><span>${def.icon} ${def.label}</span></div>`);

  Object.entries(sec.fields).forEach(([key,val])=>{
    const label = key.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase());
    let input='';
    if(key==='items'||key==='images'||key==='plans'||key==='links'){
      input=`<textarea class="sf-prop-input sf-prop-json" data-key="${key}" rows="6">${typeof val==='string'?val:JSON.stringify(val,null,2)}</textarea>`;
    } else if(typeof val==='boolean'||val==='true'||val==='false'){
      const checked=(val===true||val==='true')?'checked':'';
      input=`<label class="sf-toggle"><input type="checkbox" class="sf-prop-input" data-key="${key}" ${checked}><span class="sf-toggle-slider"></span></label>`;
    } else if(key.includes('color')||key==='bg'||key==='accent'){
      input=`<input type="color" class="sf-prop-input" data-key="${key}" value="${val}">`;
    } else if(key==='cols'||key==='height'){
      input=`<input type="number" class="sf-prop-input" data-key="${key}" value="${val}" min="1" max="6">`;
    } else {
      input=`<input type="text" class="sf-prop-input" data-key="${key}" value="${typeof val==='string'?val:JSON.stringify(val)}">`;
    }
    panel.append(`<div class="sf-prop-field"><label>${label}</label>${input}</div>`);
  });
}

function updateSectionField(uid, key, val){
  const sec = state.sections.find(s=>s.uid===uid);
  if(!sec) return;
  sec.fields[key] = val;
  const def = SECTIONS.find(s=>s.id===sec.type);
  const html = def.render({...def.fields,...sec.fields});
  $(`.sf-canvas-section[data-uid="${uid}"] .sf-section-preview`).html(html);
}

function applyPageSettings(){
  const s = state.settings;
  const canvas = $('#sf-canvas');
  canvas.attr('data-theme', s.theme||'light');
  canvas.css('font-family', s.font?`'${s.font}',sans-serif`:'');
}

function savePage(){
  state.title  = $('#sf-page-title').text().trim()||'Untitled';
  state.status = $('#sf-page-status').val();
  api('/pages/'+state.pageId,'POST',{ title:state.title, status:state.status, sections:state.sections, settings:state.settings })
    .done(()=>toast('Page saved ✓'))
    .fail(()=>toast('Save failed','error'));
}

/* ─── DRAG & DROP ─────────────────────────────────────────────────────── */
function initDragDrop(){
  $(document).on('dragstart','.sf-section-item',function(e){
    e.originalEvent.dataTransfer.setData('sf-type',$(this).data('id'));
  });
  const canvas = document.getElementById('sf-canvas');
  if(!canvas) return;
  canvas.addEventListener('dragover',e=>{ e.preventDefault(); canvas.classList.add('sf-drag-over'); });
  canvas.addEventListener('dragleave',()=>canvas.classList.remove('sf-drag-over'));
  canvas.addEventListener('drop',e=>{
    e.preventDefault();
    canvas.classList.remove('sf-drag-over');
    const type = e.dataTransfer.getData('sf-type');
    if(type) addSection(type);
  });
}

/* ─── EDITOR EVENTS ───────────────────────────────────────────────────── */
function bindEditorEvents(){
  // Section list click
  $(document).on('click','.sf-section-item',function(){ addSection($(this).data('id')); });

  // Search
  $('#sf-section-search').on('input',function(){ buildSectionList($(this).val()); });

  // Tabs
  $(document).on('click','.sf-tab',function(){
    const tab=$(this).data('tab');
    $('.sf-tab').removeClass('active');
    $(this).addClass('active');
    $('.sf-tab-content').addClass('hidden');
    $(`#tab-${tab}`).removeClass('hidden');
  });

  // Select section
  $(document).on('click','.sf-canvas-section',function(e){
    if($(e.target).closest('.sf-section-controls').length) return;
    selectSection($(this).data('uid'));
  });

  // Section controls
  $(document).on('click','.sf-ctrl-del',function(e){
    e.stopPropagation();
    const uid=$(this).closest('.sf-canvas-section').data('uid');
    state.sections = state.sections.filter(s=>s.uid!==uid);
    $(this).closest('.sf-canvas-section').remove();
    if(state.selected===uid){ state.selected=null; $('#sf-props-panel').html('<div class="sf-props-empty">Select a section to edit</div>'); }
    if(!state.sections.length) $('#sf-canvas-empty').show();
  });

  $(document).on('click','.sf-ctrl-up',function(e){
    e.stopPropagation();
    const el=$(this).closest('.sf-canvas-section');
    const uid=el.data('uid');
    const idx=state.sections.findIndex(s=>s.uid===uid);
    if(idx<1) return;
    [state.sections[idx-1],state.sections[idx]]=[state.sections[idx],state.sections[idx-1]];
    el.prev('.sf-canvas-section').before(el);
  });

  $(document).on('click','.sf-ctrl-down',function(e){
    e.stopPropagation();
    const el=$(this).closest('.sf-canvas-section');
    const uid=el.data('uid');
    const idx=state.sections.findIndex(s=>s.uid===uid);
    if(idx>=state.sections.length-1) return;
    [state.sections[idx],state.sections[idx+1]]=[state.sections[idx+1],state.sections[idx]];
    el.next('.sf-canvas-section').after(el);
  });

  $(document).on('click','.sf-ctrl-dup',function(e){
    e.stopPropagation();
    const uid=$(this).closest('.sf-canvas-section').data('uid');
    const sec=state.sections.find(s=>s.uid===uid);
    if(!sec) return;
    const copy={...sec, uid:uid(), fields:{...sec.fields}};
    const idx=state.sections.findIndex(s=>s.uid===uid);
    state.sections.splice(idx+1,0,copy);
    $(this).closest('.sf-canvas-section').after(buildSectionEl(copy));
    toast('Section duplicated');
  });

  // Props panel live edit
  $(document).on('input change','.sf-prop-input',function(){
    if(!state.selected) return;
    const key=$(this).data('key');
    let val=$(this).is('[type=checkbox]')?$(this).prop('checked'):$(this).val();
    if($(this).hasClass('sf-prop-json')){ try{ val=JSON.parse(val); }catch(e){ return; } }
    updateSectionField(state.selected, key, val);
  });

  // Page settings
  $('#sf-theme-select').on('change',function(){ state.settings.theme=$(this).val(); applyPageSettings(); });
  $('#sf-font-select').on('change',function(){ state.settings.font=$(this).val(); applyPageSettings(); });

  // Save
  $('#sf-save-btn').on('click', savePage);

  // Preview
  $('#sf-preview-btn').on('click',function(){
    savePage();
    setTimeout(()=>{ window.open(SF.admin+'?page=siteforge-preview&page_id='+state.pageId,'_blank'); },500);
  });

  // Title edit
  $('#sf-page-title').on('blur',function(){ state.title=$(this).text().trim()||'Untitled'; });

  // Keyboard save
  $(document).on('keydown',function(e){ if((e.ctrlKey||e.metaKey)&&e.key==='s'){ e.preventDefault(); savePage(); } });
}

/* ─── SETTINGS PAGE ───────────────────────────────────────────────────── */
function initSettings(){
  if(!$('#sf-settings').length) return;
  api('/settings').done(s=>{
    $('#sf-s-name').val(s.site_name||'');
    $('#sf-s-color').val(s.primary_color||'#667eea');
    $('#sf-s-font').val(s.default_font||'Inter');
  });
  $('#sf-save-settings').on('click',function(){
    api('/settings','POST',{ site_name:$('#sf-s-name').val(), primary_color:$('#sf-s-color').val(), default_font:$('#sf-s-font').val() })
      .done(()=>{ toast('Settings saved ✓'); $('#sf-settings-msg').text(''); })
      .fail(()=>toast('Save failed','error'));
  });
}

/* ─── BOOT ────────────────────────────────────────────────────────────── */
$(function(){
  initDashboard();
  initEditor();
  initSettings();
});

})(jQuery);
