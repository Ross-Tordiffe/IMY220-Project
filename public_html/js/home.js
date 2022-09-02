

const EventObject = ({event_id, event_user_id, event_title, event_date, event_location, event_image, event_user_count, event_description, event_category}) => `
    <div class="col-md-6 col-lg-3 col-12 event">
        <div class="border-0 card card-container mb-4 shadow">
            <p class="d-none">${user_id}</p>
            <p class="d-none event-id">${event_id}</p>
            <i onclick="deleteUserEvent(this)" class="fa-regular fa-trash-can delete-icon fs-3"></i>
            <div class="event-image">
                <img class="card-img-top img-fluid mx-auto d-block" src="./${image_path}" alt="${image_name}">
            </div>
            <div class="card-body event-content">
                <p class="event-title fs-4 mb-0" onclick="openEvent()">${name}</p>
                <p class="accent-heading">${category} / ${date}</p>
                <div class="d-flex justify-content-between w-100">
                    <p class="m-0"><i class="fa-solid fa-user-group pe-2 card-icon"></i><span class="me-1">${current_users}</span>/<span class="ms-1">${max_users}</span></p>
                    <p class="m-0"><i class="fa-solid fa-location-dot pe-2 event-icons"></i>${location}</p>
                </div>
            </div>
        </div>
    </div>
`;