/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/navbar.css';
import './styles/userNavbar.css';
import './styles/rightCol.css';
import './styles/events.css';
import './styles/user.css';
import './styles/search.css';
import './styles/home.css';

import './styles/modal-search.css';

import './styles/list-card.css';
import './styles/event-card.css';

import './styles/event-view.css';

import './styles/media.css';

// start the Stimulus application
const $ = require('jquery');
import './bootstrap';

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

function tagin(el, option = {}) {
    const classElement = 'tagin'
    const classWrapper = 'tagin-wrapper'
    const classTag = 'tagin-tag'
    const classRemove = 'tagin-tag-remove'
    const classInput = 'tagin-input'
    const classInputHidden = 'tagin-input-hidden'
    const defaultSeparator = ','
    const defaultDuplicate = 'false'
    const defaultTransform = input => input
    const defaultPlaceholder = ''
    const separator = el.dataset.separator || option.separator || defaultSeparator
    const duplicate = el.dataset.duplicate || option.duplicate || defaultDuplicate
    const transform = eval(el.dataset.transform) || option.transform || defaultTransform
    const placeholder = el.dataset.placeholder || option.placeholder || defaultPlaceholder

    const templateTag = value => `<span class="${classTag}">${value}<span class="${classRemove}"></span></span>`

    const getValue = () => el.value
    const getValues = () => getValue().split(separator)

        // Create
        ; (function () {
            const className = classWrapper + ' ' + el.className.replace(classElement, '').trim()
            const tags = getValue().trim() === '' ? '' : getValues().map(templateTag).join('')
            const template = `<div class="${className}">${tags}<input type="text" class="${classInput}" placeholder="${placeholder}"></div>`
            el.insertAdjacentHTML('afterend', template) // insert template after element
        })()

    const wrapper = el.nextElementSibling
    const input = wrapper.getElementsByClassName(classInput)[0]
    const getTags = () => [...wrapper.getElementsByClassName(classTag)].map(tag => tag.textContent)
    const getTag = () => getTags().join(separator)

    const updateValue = () => { el.value = getTag(); el.dispatchEvent(new Event('change')) }

    // Focus to input
    wrapper.addEventListener('click', () => input.focus())

    // Toggle focus class
    input.addEventListener('focus', () => wrapper.classList.add('focus'))
    input.addEventListener('blur', () => wrapper.classList.remove('focus'))

    // Remove by click
    document.addEventListener('click', e => {
        if (e.target.closest('.' + classRemove)) {
            e.target.closest('.' + classRemove).parentNode.remove()
            updateValue()
        }
    })

    // Remove with backspace
    input.addEventListener('keydown', e => {
        if (input.value === '' && e.keyCode === 8 && wrapper.getElementsByClassName(classTag).length) {
            wrapper.querySelector('.' + classTag + ':last-of-type').remove()
            updateValue()
        }
    })

    // Adding tag
    input.addEventListener('input', () => {
        addTag()
        autowidth()
    })
    input.addEventListener('blur', () => {
        addTag(true)
        autowidth()
    })
    autowidth()

    function autowidth() {
        const fakeEl = document.createElement('div')
        fakeEl.classList.add(classInput, classInputHidden)
        const string = input.value || input.getAttribute('placeholder') || ''
        fakeEl.innerHTML = string.replace(/ /g, '&nbsp;')
        document.body.appendChild(fakeEl)
        input.style.setProperty('width', Math.ceil(window.getComputedStyle(fakeEl).width.replace('px', '')) + 1 + 'px')
        fakeEl.remove()
    }
    function addTag(force = false) {
        const value = transform(input.value.replace(new RegExp(escapeRegex(separator), 'g'), '').trim())
        if (value === '') { input.value = '' }
        if (input.value.includes(separator) || (force && input.value != '')) {
            if (getTags().includes(value) && duplicate === 'false') {
                alertExist(value)
            } else {
                input.insertAdjacentHTML('beforebegin', templateTag(value))
                updateValue()
            }
            input.value = ''
            input.removeAttribute('style')
        }
    }
    function alertExist(value) {
        for (const el of wrapper.getElementsByClassName(classTag)) {
            if (el.textContent === value) {
                el.style.transform = 'scale(1.09)'
                setTimeout(() => { el.removeAttribute('style') }, 150)
            }
        }
    }
    function updateTag() {
        if (getValue() !== getTag()) {
            [...wrapper.getElementsByClassName(classTag)].map(tag => tag.remove())
            getValue().trim() !== '' && input.insertAdjacentHTML('beforebegin', getValues().map(templateTag).join(''))
        }
    }
    function escapeRegex(value) {
        return value.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
    }
    el.addEventListener('change', () => updateTag())
}

function onClickExecuteQuery(queryPath, pagePath, refreshElement) {
    $('#loadingModal').modal('show')

    $.ajax({
        url: queryPath,
        cache: false
    }).done(function (response) {
        $('#' + refreshElement).load(pagePath + ' #' + refreshElement)
        setTimeout(() => {
            $('#loadingModal').modal('hide')
        }, 500)
    })
}

var loadFile = function (event) {
    var output = document.getElementById('image_output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.display = 'block';
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};

for (const el of document.querySelectorAll('.tagin')) {
    tagin(el)
}