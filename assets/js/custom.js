$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({pageNotFound : 'error_404'}); // initialize


  app.route({
    view: 'home',
    load: 'home.html'
  });
  
  app.route({
    view: 'hotels',
    load: 'hotels.html'
  });

  app.route({
    view: 'account',
    load: 'account.html'
  });

  app.route({
    view: 'faq',
    load: 'faq.html'
  });

  app.route({
    view: 'covid-deals',
    load: 'specialCovidDeals.html'
  });

  // run app
  app.run();

});
