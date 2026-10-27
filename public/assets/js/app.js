(function ($) {
  'use strict';

  // sidebar submenu collapsible js
  $(".sidebar-menu .dropdown").on("click", function(){
    var item = $(this);
    item.siblings(".dropdown").children(".sidebar-submenu").slideUp();

    item.siblings(".dropdown").removeClass("dropdown-open");

    item.siblings(".dropdown").removeClass("open");

    item.children(".sidebar-submenu").slideToggle();

    item.toggleClass("dropdown-open");
  });

  $(".sidebar-toggle").on("click", function(){
    $(this).toggleClass("active");
    $(".sidebar").toggleClass("active");
    $(".dashboard-main").toggleClass("active");
  });

  $(".sidebar-mobile-toggle").on("click", function(){
    $(".sidebar").addClass("sidebar-open");
    $("body").addClass("overlay-active");
  });

  $(".sidebar-close-btn").on("click", function(){
    $(".sidebar").removeClass("sidebar-open");
    $("body").removeClass("overlay-active");
  });

  //to keep the current page active
  $(function () {
    for (
      var nk = window.location,
        o = $("ul#sidebar-menu a")
          .filter(function () {
            return this.href == nk;
          })
          .addClass("active-page") // anchor
          .parent()
          .addClass("active-page");
      ;

    ) {
      // li
      if (!o.is("li")) break;
      o = o.parent().addClass("show").parent().addClass("open");
    }
  });

const THEME_STORAGE_KEY = "theme";
const themeButton = document.querySelector("[data-theme-toggle]");
const systemThemeQuery = window.matchMedia("(prefers-color-scheme: dark)");

function isSupportedTheme(theme) {
  return theme === "dark" || theme === "light";
}

function getStoredTheme() {
  try {
    const storedTheme = window.localStorage.getItem(THEME_STORAGE_KEY);
    return isSupportedTheme(storedTheme) ? storedTheme : null;
  } catch (error) {
    return null;
  }
}

function getPreferredTheme() {
  const storedTheme = getStoredTheme();

  if (storedTheme) {
    return storedTheme;
  }

  return systemThemeQuery.matches ? "dark" : "light";
}

function updateThemeButton(theme) {
  if (!themeButton) {
    return;
  }

  const isDark = theme === "dark";
  const actionLabel = isDark ? "Aktifkan tema terang" : "Aktifkan tema gelap";

  themeButton.dataset.themeIcon = theme;
  themeButton.setAttribute("aria-label", actionLabel);
  themeButton.setAttribute("aria-pressed", String(isDark));
  themeButton.setAttribute("title", actionLabel);
}

function applyTheme(theme, options = {}) {
  const persist = options.persist === true;
  const root = document.documentElement;

  if (!isSupportedTheme(theme)) {
    return;
  }

  root.setAttribute("data-theme", theme);
  root.style.colorScheme = theme;
  updateThemeButton(theme);

  if (persist) {
    try {
      window.localStorage.setItem(THEME_STORAGE_KEY, theme);
    } catch (error) {
      // The theme remains active for this page when storage is unavailable.
    }
  }

  window.dispatchEvent(new CustomEvent("admin:themechange", {
    detail: { theme }
  }));
}

let currentThemeSetting = getPreferredTheme();
applyTheme(currentThemeSetting);

window.requestAnimationFrame(function () {
  document.documentElement.classList.remove("theme-initializing");
});

if (themeButton) {
  themeButton.addEventListener("click", function () {
    currentThemeSetting = currentThemeSetting === "dark" ? "light" : "dark";
    applyTheme(currentThemeSetting, { persist: true });
  });
}

function handleSystemThemeChange(event) {
  if (getStoredTheme()) {
    return;
  }

  currentThemeSetting = event.matches ? "dark" : "light";
  applyTheme(currentThemeSetting);
}

if (typeof systemThemeQuery.addEventListener === "function") {
  systemThemeQuery.addEventListener("change", handleSystemThemeChange);
} else if (typeof systemThemeQuery.addListener === "function") {
  systemThemeQuery.addListener(handleSystemThemeChange);
}

window.addEventListener("pageshow", function () {
  currentThemeSetting = getPreferredTheme();
  applyTheme(currentThemeSetting);
});


// =========================== Table Header Checkbox checked all js Start ================================
$('#selectAll').on('change', function () {
  $('.form-check .form-check-input').prop('checked', $(this).prop('checked')); 
}); 

  // Remove Table Tr when click on remove btn start
  $('.remove-btn').on('click', function () {
    $(this).closest('tr').remove(); 

    // Check if the table has no rows left
    if ($('.table tbody tr').length === 0) {
      $('.table').addClass('bg-danger');

      // Show notification
      $('.no-items-found').show();
    }
  });
  // Remove Table Tr when click on remove btn end
})(jQuery);
