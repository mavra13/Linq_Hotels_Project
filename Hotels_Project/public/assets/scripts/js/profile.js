
class Booking {
    constructor(id, roomId, totalPrice, checkInDate, checkOutDate, img, name, address, descriptionShort, roomType) {
        this.id = id;
        this.roomId = roomId;
        this.totalPrice = totalPrice;
        this.checkInDate = checkInDate;
        this.checkOutDate = checkOutDate;
        this.img = img;
        this.name = name;
        this.address = address;
        this.descriptionShort = descriptionShort;
        this.roomType = roomType;
    }
}
class BookingsList {
    constructor(listSelector) {
        this.list = [];
        this.$list = document.querySelector(listSelector);
        this.addEvents();
    }
	addNoBookingsMsg() {
		const $mesg = '<p class="noResultsMsg">You have no bookings yet.</p>';
		this.$list.innerHTML = $mesg;
	}
    addEvents() {
        this.$list.addEventListener("click", this.handleBookingBtnClick.bind(this));
    }
    addBooking(room) {
        this.list.push(room);
        this.sortItems();
        this.renderItems();
    }
    handleBookingBtnClick(e) {
        const className = e.target.className;
        if (className.includes("btnGoToRoom")) {
            this.goToRoom(e);
        }
    }
    goToRoom(e) {
        const roomId = e.target.closest(".roomInfo").dataset.roomId;
        console.log(roomId);
		window.location.href = `room.php?room_id=${roomId}`;
    }
    sortItems() {
        this.list.sort((a, b) => {
            return new Date(b.checkInDate) - new Date(a.checkInDate);
        })
    }
    renderItems() {
        const bookingTemplate = this.list.map((booking, i) => {
            const { id, roomId, totalPrice, checkInDate, checkOutDate, img, name, address, descriptionShort, roomType } = booking;

            return `<section class="roomInfo" data-room-id="${roomId}" data-booking-id="${id}">
                <section class="roomInfoMain display-flex align-items-top justify-content-between">
                    <img src="${img}" class="roomImageListView">
                    <section class="roomDetails">
                        <p class="roomTitle fsc-16">${name}</p>
                        <p class="roomAddress fsc-12">${address}</p>
                        <p class="roomSmallDescription fsc-12">${descriptionShort}</p>
                        <div class="display-flex justify-content-end">
                            <button type="button" class="buttonGeneric custom-btn fsc-12 btnGoToRoom">Go to Room Page</button>
                        </div>
                    </section>
                </section>
                <section class="roomInfoFooter display-flex align-items-center">
                    <p class="roomPrice text-center fsc-12">Total Cost: ${totalPrice} &#x20AC</p>
                    <div class="roomFooterRightSection display-flex align-items-center text-center">
                        <p class="roomCheckInDate flex-item fsc-12">Check-in Date: ${checkInDate}</p>
                        <p class="roomCheckOutDate flex-item fsc-12">Check-out Date: ${checkOutDate}</p>
                        <p class="roomType flex-item fsc-12">Type of Room: ${roomType}</p>
                    </div>
                </section>
            </section>`;

        });

        this.$list.innerHTML = bookingTemplate.join("");
    }
}

class Favourite {
    constructor(roomId, name) {
        this.roomId = roomId;
        this.name = name;
    }
}
class FavouritesList {
    constructor(listSelector) {
        this.list = [];
        this.$list = document.querySelector(listSelector);
        this.addEvents();
    }
	addNoFavouritesMsg() {
		const $mesg = '<p class="noResultsMsg">You have no favourite hotels yet.</p>';
		this.$list.innerHTML = $mesg;
	}
    addEvents() {
        this.$list.addEventListener("click", this.handleFavouriteClick.bind(this));
    }
    addFavourite(room) {
        this.list.push(room);
        this.renderItems();
    }
    handleFavouriteClick(e) {
        if(!e.target.classList.contains("noResultsMsg")) {
        	this.goToRoom(e);
		}
    }
    goToRoom(e) {
        const roomId = e.target.dataset.roomId; //
        console.log(roomId);
		window.location.href = `room.php?room_id=${roomId}`;
    }

    renderItems() {
        const favouriteTemplate = this.list.map((favourite, i) => {
            const { roomId, name } = favourite;
            return `<li data-room-id="${roomId}">${name}</li>`;
        });

        this.$list.innerHTML = favouriteTemplate.join("");
    }

}

class Rating {
    constructor(roomId, name, rate) {
        this.roomId = roomId;
        this.name = name;
        this.rate = rate;
    }
}
class RatingsList {
    constructor(listSelector) {
        this.list = [];
        this.$list = document.querySelector(listSelector);
        this.addEvents();
    }
	addNoRatingsMsg() {
		const $mesg = '<p class="noResultsMsg">You haven\'t made a review yet.</p>';
		this.$list.innerHTML = $mesg;
	}
    addEvents() {
        this.$list.addEventListener("click", this.handleRatingClick.bind(this));
    }
    addRating(room) {
        this.list.push(room);
        this.renderItems();
    }
    handleRatingClick(e) {
		if(!e.target.classList.contains("noResultsMsg")) {
        	let roomId = e.target.dataset.roomId;
        	if (!roomId) {
            	roomId = e.target.closest("li").dataset.roomId;
        	} 
        	this.goToRoom(roomId);
		}
    }
    goToRoom(id) {
        console.log(id);
		window.location.href = `room.php?room_id=${id}`;
    }

    renderItems() {
        // const $listRooms = this.$list.querySelector(".items");
        const ratingTemplate = this.list.map((rating, i) => {
            const { roomId, name, rate } = rating;
            let star = '<span class="fa fa-solid fa-star"></span>';
            let starChecked = '<span class="fa fa-solid fa-star checked"></span>';
            let ratingStars = '';
            for(let r=1; r<=5; r++) {
                if(r>rate) {
                    ratingStars += star;
                } else {
                    ratingStars += starChecked;
                }
            }
            return `<li data-room-id="${roomId}">${name}
                        </br>${ratingStars}
                    </li>`;
        });

        this.$list.innerHTML = ratingTemplate.join("");
    }
}
const bookingRoomsList = new BookingsList(".roomsListBookings");
const favouriteRoomsList = new FavouritesList(".favouritesList");
const ratingRoomsList = new RatingsList(".reviewsList");

//const booking1 = new Booking(1, 18, 500, "2018-04-27", "2018-04-30", "assets/images/room-2.jpg", "MEGALI VRETANIA HOTEL", "ATHENS SYNTAGMA", "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.", "Double Room");
//const booking2 = new Booking(2, 46, 350, "2018-06-20", "2018-09-25", "assets/images/room-1.jpg", "HILTON HOTEL", "ATHENS ZWGRAFOU", "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.", "Single Room");

//bookingRoomsList.addBooking(booking1);
//bookingRoomsList.addBooking(booking2);
//const favourite1 = new Favourite(18, "Megali Vretania Hotel");

//favouriteRoomsList.addFavourite(favourite1);
//const rating1 = new Rating(46, "Hilton Hotel", 4);
//const rating2 = new Rating(18, "Megali Vretania Hotel", 5);

//ratingRoomsList.addRating(rating1);
//ratingRoomsList.addRating(rating2);