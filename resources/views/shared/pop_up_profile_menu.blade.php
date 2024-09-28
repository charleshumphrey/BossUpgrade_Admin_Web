<div id="profile-menu" class="hidden cursor-pointer bg-white flex absolute top-1 right-1 mr-1 flex flex-col rounded-md shadow-md">
    <ul>
        <li>
            <a href="#" class="transition hover:bg-zinc-100 flex py-2 px-5 items-center text-gray-300 text-sm border-b">
                <i class="fa-solid fa-user mr-3 p-1"></i>
                <p class="text-black font-poppins_regular">Profile</p>
            </a>
            <a href="{{ route('change-password') }}" class="transition hover:bg-zinc-100 flex py-2 px-5 items-center text-gray-300 text-sm border-b">
                <i class="fa-solid fa-lock mr-3 p-1"></i>
                <p class="text-black font-poppins_regular">Change Password</p>
            </a>
            <a class="transition hover:bg-zinc-100 flex py-2 px-5 items-center text-red-500 text-sm">
                <i class="fa-solid fa-power-off mr-3 p-1"></i>
                <p class="font-poppins_regular">Log Out</p>
            </a>
        </li>
    </ul>
</div>