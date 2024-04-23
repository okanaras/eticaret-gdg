$(document).ready(() => {
  $(".search-open").on("click", () => {
    $(".search-inside").fadeIn(); // fadIn jquery dnone olani efektli bir sekilde gozukturuyor
  });

  $(".search-close").on("click", () => {
    $(".search-inside").fadeOut(); // fadOut jquery gozukeni efektli bir sekilde dnone a cekiyor
  });
});
