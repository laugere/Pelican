function onClickBtnGoTo(communityGoToPath, communityId) {
  $('#loadingModal').modal('show')

  $.ajax({ url: communityGoToPath, cache: false }).done(function (response) {
    $('#' + communityId).load('/community #' + communityId)
    setTimeout(() => {
      $('#loadingModal').modal('hide')
    }, 500)
  })
}

function onClickGoTo(event) {
  event.preventDefault()

  $('#loadingModal').modal('show')

  const url = this.href

  $.ajax({ url: url, type: 'POST', async: true }).done(function (response) {
    $('#bodyFrame').load(url + ' #bodyFrame')
    $('#navbarNav').collapse('hide')
    $('#userNavBarPanel').collapse('hide')
    setTimeout(() => {
      $('#loadingModal').modal('hide')
    }, 500)
  })
}

document.querySelectorAll('a.nav-link').forEach(function (link) {
  link.addEventListener('click', onClickGoTo)
})
