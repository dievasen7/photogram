function get_profile_card(user) {
    let details = user.details;
    let card = document.createElement('div');
    card.className = 'card mb-3';
    let row = document.createElement('div');
    row.className = 'row g-0';
    let imageContainer = document.createElement('div');
    imageContainer.className = 'col-md-2';
    let image = document.createElement('img');
    image.src = details.profile_photo;
    image.alt = details.display_name;
    image.title = details.display_name;
    image.style.height = '100%';
    image.className = 'img-fluid rounded-start';
    imageContainer.appendChild(image);
    row.appendChild(imageContainer);
    let bodyContainer = document.createElement('div');
    bodyContainer.className = 'col-md-10';
    let cardBody = document.createElement('div');
    cardBody.className = 'card-body';
    let title = document.createElement('h5');
    title.className = 'card-title';
    title.innerHTML = '<a href="/p/' + user.id + '" title="View profile">' + details.display_name + '</a>';
    cardBody.appendChild(title);;
    let text = document.createElement('p');
    text.className = 'card-text';
    text.innerHTML = details.bio;
    cardBody.appendChild(text);
    bodyContainer.appendChild(cardBody);
    row.appendChild(bodyContainer);
    card.appendChild(row);

    return card;
}

function get_modal(title, content) {
    let modal = document.createElement('div');
    modal.className = 'modal';
    modal.tabIndex = -1;
    let dialog = document.createElement('div');
    dialog.className = 'modal-dialog';
    let modalContent = document.createElement('div');
    modalContent.className = 'modal-content';
    let header = document.createElement('div');
    header.className = 'modal-header';
    header.innerHTML = '<h5 class="modal-title">' + title + '</div>';
    header.innerHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    let body = document.createElement('div');
    body.className = 'modal-body';
    let footer = document.createElement('div');
    footer.className = 'modal-footer';
    footer.innerHTML = '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>';
    modalContent.appendChild(header);
    body.appendChild(content);
    modalContent.appendChild(body);
    modalContent.appendChild(footer);
    dialog.appendChild(modalContent);
    modal.appendChild(dialog);

    return new bootstrap.Modal(modal);
}

function create_modal_with_users(title, users, onEmptyContent) {
    let listGroup;
    if (users.length) {
        listGroup = document.createElement('ul');
        listGroup.className = 'list-group';
        for (let i in users) {
            let profile = users[i];
            let item = document.createElement('li');
            item.className = 'list-group-item';
            item.appendChild(get_profile_card(profile));
            listGroup.appendChild(item);
        }
    } else {
        listGroup = document.createElement('p');
        listGroup.innerHTML = onEmptyContent;
    }

    return get_modal(title, listGroup);
}

let links = document.querySelectorAll('.user-modal-link');
for (let element of links) {
    element.addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        axios.get(event.target.href).then((response) => {
            let onEmptyContent = event.target.dataset.emptyContent ? event.target.dataset.emptyContent : '';
            create_modal_with_users(event.target.title, response.data, onEmptyContent).show();
        });
    });
}