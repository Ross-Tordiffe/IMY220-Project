/*
// --- Event Object (included in default.js) ---
const EventObject = ({event_id, event_user_id, event_title, event_date, event_location, event_image, event_user_count, event_description, event_category, event_tags, event_user_name, event_user_image}) => `
    <div class="event">
        <div class="card card-container mb-4 shadow event-card d-flex justify-content-center">
            <p class="d-none event-user-id">${event_user_id}</p>
            <p class="d-none event-id">${event_id}</p>
            <div class="event-image position-relative">
                <img class="img-fluid mx-auto d-block" src="public_html/img/event/${event_image}" alt="${event_image}"/>
                <div class="d-flex align-items-center event-user-loc w-100">
                    <img src="public_html/img/user/${event_user_image}" class="col-2 img-fluid event-user-img my-0 mx-2"/>
                    <div class="col-xxl-8 col-xl-7 col-lg-6 event-name-loc d-flex flex-column">
                        <div class="event-user-name">${event_user_name}</div>
                        <span class="event-location"><i class="fas fa-map-marker-alt pe-1"></i>${event_location}</span>
                    </div>
                    <div class="col-2 event-join d-flex justify-content-between align-items-center">
                        <div class="event-user-count d-flex align-items-center">
                            <span class="small pe-none">${event_user_count}</span>
                            <i class="fas fa-users ps-1 pe-none"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body event-content pt-1 px-3">
                <p class="event-title card-title mb-0">${event_title}</p>
                <div class="event-date d-flex justify-content-between">
                    <span class="small">${event_category}</span>
                    <span class="small">${event_date}<i class="fas fa-calendar-alt ps-1"></i></span>
                </div>
                <!-- small line -->
                <div class="extra">
                    <hr class="my-2"></hr>
                    <div class="event-description card-text small">${event_description}</div>
                    <div class="hashtag-container d-flex flex-wrap">
                        ${
                            event_tags.map((tag) => {
                                return `<span class="event-hashtag small badge">${tag}</span>`
                            }).join('')
                        }
                    </div>
                </div>
            </div>
        </div>
    </div>
`;
*/

const UserObject = ({user_id, user_username, user_image}) => {
    let userImg = 'public_html/img/user/' + user_image;
    return `
        <li class="user d-flex align-item-center">
            <div class="user-image">
                <img src="${userImg}" alt="Profile Image">
            </div>
            <div class="user-name ps-2 d-flex justify-content-between align-items-center w-100 me-3">
                <span>${user_username}</span>
            </div>
            <div class="d-none user-id">${user_id}</div>
        </li>
    `;
}

// === Promise Functions ===

const getEvents = async () => {
    const data = await new Promise((resolve, reject) => {
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "getEvents",
                scope: "global"
            },
            success: (data) => {
                resolve(data);
            },
            error: (error) => {
                reject(error);
            }
        });
    });
    return JSON.parse(data);
}

const getUsers = async () => {
    const data = await new Promise((resolve, reject) => {
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "getUsers",
            },
            success: (data) => {
                resolve(data);
            },
            error: (error) => {
                reject(error);
            }
        });
    });
    return JSON.parse(data);
}

const getAll = async () => {
    
    let data = await Promise.all([getEvents(), getUsers()]);
    // return json;
    console.log(data);
    return data;

}


// === Jquery Autocomplete & Fuzzy Search ===

const levenshteinDistance = (s, t) => {
    if (!s.length) return t.length;
    if (!t.length) return s.length;

    return Math.min(
        levenshteinDistance(s.substr(1), t) + 1,
        levenshteinDistance(t.substr(1), s) + 1,
        levenshteinDistance(s.substr(1), t.substr(1)) + (s.charAt(0).toLowerCase() !== t.charAt(0).toLowerCase() ? 1 : 0)
    );
}

// === Helper Functions ===

const limit = (arr, size) => {
    if(arr.length > size) {
        return arr.slice(0, size);
    }
    return arr;
}

// const getFuzzyResults = (search, arr) => {
//     let filtered = [];
//     let words;
//     for (let i = 0; i < arr.length; i++) {
//         words = arr[i].split(" ");

//         for (let j = 0; j < words.length; j++) {
//             if (levenshteinDistance(words[j], search) <= 3) {
//                 filtered.push(arr[i]);
//                 break;
//             }
//         }
//     }
// }

const makeSearch = (search, type) => {

    if($(".search-bar-row").hasClass("no-search")) {
        $(".search-bar-row").removeClass("no-search");
    }

    let filtered = [];

    if(type === "tag") {
        // filter all events which include the tag
        filtered = events.filter((event) => event.event_tags.includes(search));
        if (filtered.length === 0 ) {
            events.forEach((event) => {
                event.event_tags.forEach((tag) => {
                    let words = tag.split(" ");
                    words.forEach((word) => {
                        if (levenshteinDistance(word, search) <= 3) {
                            filtered.push(event);
                        }
                    });
                });
            });
        }
    }
    else if(type === "user") {
        // filter all events which include the user (then remove @ from each username)
        filteredUsernames = userNames.filter((user) => user.toLowerCase().includes(search.toLowerCase()));
        filteredUsernames = filteredUsernames.map((user) => user.replace("@", ""));
        if(filteredUsernames.length === 0) {
            userNames.forEach((user) => {
                let words = user.split(" ");
                words.forEach((word) => {
                    if (levenshteinDistance(word, search) <= 3) {
                        filteredUsernames.push(user);
                    }
                });
            });
        }
        console.log(filteredUsernames);
        filtered = users.filter((user) => filteredUsernames.includes(user.user_username));
        console.log(filtered);
    }
    else if(type === "date") {
        //convert dd.mm.yyyy or dd/mm/yyyy to yyyy-mm-dd
        let date = search.split(/\.|\//);
        date = date[2] + "-" + date[1] + "-" + date[0];
        filtered = events.filter((event) => event.event_date.includes(date));
    }   
    else {
        filtered = events.filter((event) => event.event_title.toLowerCase().includes(search.toLowerCase()));
        if (filtered.length === 0 ) {
            events.forEach((event) => {
                let words = event.event_title.split(" ");
                words.forEach((word) => {
                    if (levenshteinDistance(word, search) <= 3) {
                        filtered.push(event);
                    }
                });
            });
        }
    }

    $(".event-col").empty();
    $(".explore-box-user-list").empty();

    console.log(filtered);
    if(filtered.length == 0)
    {
        $(".explore-box .no-results").css("display", "flex");
        if($(".explore-box-user-list").css("display") === "block") {
            $(".explore-box-user-list").css("display", "none");
        }
        return;
    }
    else {
        $(".explore-box .no-results").css("display", "none");
    }

    if(type !== "user") {
        // fill the search results
        if($(".explore-box-user-list").css("display") === "block") {
            $(".explore-box-user-list").css("display", "none");
        }
        // Based on the screen size distribute events between event-cols
        if($(window).width() >= 1200) {
            for(let i = 0; i < filtered.length; i++) {
                if(i % 3 === 0) {
                    $(".event-col-1").append(EventObject(filtered[i]));
                }
                else if(i % 3 === 1) {
                    $(".event-col-2").append(EventObject(filtered[i]));
                }
                else {
                    $(".event-col-3").append(EventObject(filtered[i]));
                }
            }
        }
        else if($(window).width() >= 992) {
            for(let i = 0; i < filtered.length; i++) {
                if(i % 2 === 0) {
                    $(".event-col-1").append(EventObject(filtered[i]));
                }
                else {
                    $(".event-col-2").append(EventObject(filtered[i]));
                }
            }
        }
        else {
            for(let i = 0; i < filtered.length; i++) {
                $(".event-col-1").append(EventObject(filtered[i]));
            }
        }
    }
    else {
        //({user_id, user_username, user_image})

        if($(".explore-box-user-list").css("display") === "none") {
            $(".explore-box-user-list").css("display", "block");
        }
        console.log(filtered);
        let displayUsers = filtered.map((user) => { 
            return {user_id: user.user_id, user_username: user.user_username, user_image: user.user_image};
        });
        
        displayUsers.forEach((user) => {
            $(".explore-box-user-list").append(UserObject(user));
        });
    }
} 

const handleSearch = (search) => {

    let type = "title";
    // if the search is a hashtag
    if(search.charAt(0) === "#") {
        type = "tag";
        const results = eventTags.filter((tag) => tag.toLowerCase().includes(search.toLowerCase()));
        $(".explore-search").autocomplete("option", "source", limit(results, 5));
    }
    // if the search is a username
    else if(search.charAt(0) === "@") {
        type = "user";
        const results = userNames.filter((user) => user.toLowerCase().includes(search.toLowerCase()));
        $(".explore-search").autocomplete("option", "source", limit(results, 5)); 
    }
    // if the search is a date
    else if(search.match(/^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$|^(0[1-9]|1[012])[- /.](19|20)\d\d$|^(19|20)\d\d$|^(0[1-9]|[12][0-9]|3[01])(st|nd|rd|th)\s(January|February|March|April|May|June|July|August|September|October|November|December)\s(19|20)\d\d$|^(0[1-9]|[12][0-9]|3[01])(st|nd|rd|th)\s(January|February|March|April|May|June|July|August|September|October|November|December)$|^(January|February|March|April|May|June|July|August|September|October|November|December)\s(19|20)\d\d$|^(January|February|March|April|May|June|July|August|September|October|November|December)$|^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])$|^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$/)) {
        type = "date";
    }
    // if the search is plain text
    else {
        const results = eventNames.filter((event) => event.toLowerCase().includes(search.toLowerCase()));
        $(".explore-search").autocomplete("option", "source", limit(results, 5));
    }

    return type;
}


// === On Load === 
// es6 functions only

let all = [];
let events = [];
let users = [];
let eventNames = [];
let userNames = [];
let eventTags = [];

$(() => {

    getAll().then((data) => {
        events = data[0].data;
        users = data[1].data;
        let user_id = $("#user-id").text();
        users = users.filter((user) => user.user_id != user_id);
        
        eventNames = events.map((event) => event.event_title);
        eventTags = events.map((event) => event.event_tags).flat();
        userNames = users.map((user) => "@" + user.user_username);
        all = eventNames.concat(eventTags, userNames);

        // --- Autocomplete on focus ---
        $(".explore-search").autocomplete({
            source: (request, response) => {
                const results = $.ui.autocomplete.filter(all, request.term);
                response(results.slice(0, 5));
            },
            maxLength: 5,
            select: (event, ui) => {
                console.log(ui.item.value)
            },
            focus: (event, ui) => {
                $(".explore-search").val(ui.item.value);
            }
        });
    });

    $(".explore-search").on("keyup", (e) => {

        const search = $(".explore-search").val().trim();
        if(search.length > 0) {
         
            let type = handleSearch(search);
            if(e.keyCode === 13) {
                makeSearch(search, type);
            }
        }
        else {
            $(".event-col").empty();
            $(".explore-box-user-list").empty();
            if($(".explore-box .no-results").css("display") === "flex") {
                $(".explore-box .no-results").css("display", "none");
            }
            if($(".explore-box-user-list").css("display") === "block") {
                $(".explore-box-user-list").css("display", "none");
            }
            if(!$(".search-bar-row").hasClass("no-search")) {
                $(".search-bar-row").addClass("no-search");
            }
        }
    });

    $(".explore-box-user-list").on("click", ".user", (e) => {
        console.log(e.target);
        while(!$(e.target).hasClass("user")) {
            e.target = e.target.parentNode;
        }
        let userId = $(e.target).find(".user-id").text();
        window.location.href = `profile.php?user_id=${userId}`;
    });

    $(".explore-submit").on("click", () => {
        const search = $(".explore-search").val().trim();
        let type = handleSearch(search);
        if(search.length > 0) {
            makeSearch(search);
        }
    });

})

