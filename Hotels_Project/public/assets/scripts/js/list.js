class Room {
    constructor(id, img, name, address, descriptionShort, price, guestsCount, roomType) {
        this.id = id;
        this.img = img;
        this.name = name;
        this.address = address;
        this.descriptionShort = descriptionShort;
        this.price = price;
        this.guestsCount = guestsCount;
        this.roomType = roomType;
    }
	
}
class RoomsList {
    constructor(listSelector) {
        this.list = [];
        this.$list = document.querySelector(listSelector);
        this.addEvents();
    }
    addEvents() {
        this.$list.addEventListener("click", this.handleRoomBtnClick.bind(this));
    }
	clearList() {
		this.$list.innerHTML = "";
		this.list = [];
	}
	addNoResultsMsg() {
		const $mesg = '<p class="noResultsMsg">No results match your search criteria.</p>';
		this.$list.innerHTML = $mesg;
	}
    addRoom(room) {
        this.list.push(room);
        this.renderItems();
    }
    handleRoomBtnClick(e) {
        const className = e.target.className;
        if (className.includes("btnGoToRoom")) {
            this.goToRoom(e);
        }
    }
    goToRoom(e) {
        const roomId = e.target.closest(".roomInfo").dataset.roomId;
		const checkInDate = document.querySelector('#checkInDate').value;
		const checkOutDate = document.querySelector('#checkOutDate').value;
        console.log(roomId);
		window.location.href = `room.php?room_id=${roomId}&checkInDate=${checkInDate}&checkOutDate=${checkOutDate}`;
    }
    renderItems() {
        const roomTemplate = this.list.map((room, i) => {
            const { id, img, name, address, descriptionShort, price, guestsCount, roomType } = room;

            return `<section class="roomInfo" data-room-id="${id}">
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
                        <p class="roomPrice text-center fsc-12">Per Night: ${price} &#x20AC</p>
                        <div
                            class="roomGuestsTypeWraper roomFooterRightSection display-flex align-items-center text-center">
                            <p class="roomGuests flex-item fsc-12">Count of Guests: ${guestsCount}</p>
                            <p class="roomType flex-item fsc-12">Type of Room: ${roomType}</p>
                        </div>
                    </section>
                </section>`;

        });

        this.$list.innerHTML = roomTemplate.join("");
    }
}

const searchRoomList = new RoomsList(".roomsListSearch");

