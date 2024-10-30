const KUROCOEDGE_CACHE_CLEAR_STATUS_PROGRESS = 101;
const KUROCOEDGE_CACHE_CLEAR_STATUS_SUCCESS  = 102;
const KUROCOEDGE_CACHE_CLEAR_STATUS_FAILED   = 103;
const KUROCOEDGE_SYNC_NOW_STATUS_PROGRESS    = 105;
const KUROCOEDGE_SYNC_NOW_STATUS_SUCCESS     = 106;
const KUROCOEDGE_SYNC_NOW_STATUS_FAILED      = 107;

const kurocoedge_configuration_data = kurocoedge_script_obj || {};
const clear_cache_progress_el = document.getElementById('kurocoedge_cache_clear_processing_alert');
const clear_cache_success_el  = document.getElementById('kurocoedge_cache_clear_success_alert');
const clear_cache_failed_el   = document.getElementById('kurocoedge_cache_clear_failed_alert');
const sync_now_progress_el    = document.getElementById('kurocoedge_settings_sync_now_processing_alert');
const sync_now_success_el     = document.getElementById('kurocoedge_settings_sync_now_success_alert');
const sync_now_failed_el      = document.getElementById('kurocoedge_settings_sync_now_failed_alert');


if(document.getElementById('setting-error-kurocoedge_connection_cannot_enable') !== null) {
  document.getElementById("kurocoedge_general_enable").checked = true;
}

const alertCloseHandler = e => {
  e.target.parentElement.classList.add("hidden");
}

const showCacheAlert = state => {
  clear_cache_progress_el.classList.add('hidden');
  clear_cache_success_el.classList.add('hidden');
  clear_cache_failed_el.classList.add('hidden');

  switch(state) {
    case KUROCOEDGE_CACHE_CLEAR_STATUS_PROGRESS:
      clear_cache_progress_el.classList.remove('hidden');
      break;
    case KUROCOEDGE_CACHE_CLEAR_STATUS_SUCCESS:
      clear_cache_success_el.classList.remove('hidden');
      break;
    case KUROCOEDGE_CACHE_CLEAR_STATUS_FAILED:
      clear_cache_failed_el.classList.remove('hidden');
      break;
    default:
      break;
  }
}

const showSyncNowAlert = state => {
  sync_now_progress_el.classList.add('hidden');
  sync_now_success_el.classList.add('hidden');
  sync_now_failed_el.classList.add('hidden');

  switch(state) {
    case KUROCOEDGE_SYNC_NOW_STATUS_PROGRESS:
      sync_now_progress_el.classList.remove('hidden');
      break;
    case KUROCOEDGE_SYNC_NOW_STATUS_SUCCESS:
      sync_now_success_el.classList.remove('hidden');
      break;
    case KUROCOEDGE_SYNC_NOW_STATUS_FAILED:
      sync_now_failed_el.classList.remove('hidden');
      break;
    default:
      break;
  }
}

const clearCache = e => {
  e.preventDefault();
  (async _ => {
    try {
      showCacheAlert(KUROCOEDGE_CACHE_CLEAR_STATUS_PROGRESS);
      const url = `${kurocoedge_configuration_data.base_url}/wp-json/kurocoedge/v${kurocoedge_configuration_data.version}/clearcache`;
      const response = await fetch(url);
      const body = await response.json();
      if(response.status == 200 && body.status == "ok") {
        showCacheAlert(KUROCOEDGE_CACHE_CLEAR_STATUS_SUCCESS);
      } else {
        showCacheAlert(KUROCOEDGE_CACHE_CLEAR_STATUS_FAILED);
      }
    } catch(e) {
      showCacheAlert(KUROCOEDGE_CACHE_CLEAR_STATUS_FAILED);
    }
  })();
}

const syncSettings = e => {
  e.preventDefault();
  (async _ => {
    try {
      showSyncNowAlert(KUROCOEDGE_SYNC_NOW_STATUS_PROGRESS);
      const url = `${kurocoedge_configuration_data.base_url}/wp-json/kurocoedge/v${kurocoedge_configuration_data.version}/sync`;
      const response = await fetch(url);
      const body = await response.json();
      if(response.status == 200 && body.status == "ok") {
        showSyncNowAlert(KUROCOEDGE_SYNC_NOW_STATUS_SUCCESS);
      } else {
        showSyncNowAlert(KUROCOEDGE_SYNC_NOW_STATUS_FAILED);
      }
    } catch(e) {
      showSyncNowAlert(KUROCOEDGE_SYNC_NOW_STATUS_FAILED);
    }

  })();
}

const init = () => {
  if(kurocoedge_configuration_data.enable === "1") {
    document.getElementById("kurocoedge_general_clear_cache_now").addEventListener('click', clearCache);
    document.getElementById("kurocoedge_general_sync_settings_now").addEventListener('click', syncSettings);
  } else {
    document.getElementById("kurocoedge_general_clear_cache_now").addEventListener('click', e => e.preventDefault());
    document.getElementById("kurocoedge_general_sync_settings_now").addEventListener('click', e => e.preventDefault());
  }
  clear_cache_progress_el.querySelector('button.notice-dismiss').addEventListener('click', alertCloseHandler);
  clear_cache_success_el.querySelector('button.notice-dismiss').addEventListener('click',  alertCloseHandler);
  clear_cache_failed_el.querySelector('button.notice-dismiss').addEventListener('click',   alertCloseHandler);

  sync_now_progress_el.querySelector('button.notice-dismiss').addEventListener('click', alertCloseHandler);
  sync_now_success_el.querySelector('button.notice-dismiss').addEventListener('click', alertCloseHandler);
  sync_now_failed_el.querySelector('button.notice-dismiss').addEventListener('click', alertCloseHandler);
}

window.addEventListener('load', init);
