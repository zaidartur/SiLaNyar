<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
  breadcrumbs?: any
}>()
</script>

<template>
    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-customDarkGreen dark:bg-gray-800">
            <Link :href="route('dashboard')" class="flex items-center ps-2.5 mb-5">
            <img src="/assets/assetslandingpage/LogoDlh.png" class="h-10 me-3 sm:h-9" alt="Logo" />
            <span class="self-center text-2xl font-bold whitespace-nowrap text-white">SiLaNyar</span>
            </Link>
            <ul class="space-y-4 font-medium">
                <li>
                    <Link :href="route('customer.dashboard')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-green-500">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center">
                        <svg width="35" height="26" viewBox="0 0 35 26" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="35" height="26" fill="url(#pattern0_1393_1239)" />
                            <defs>
                                <pattern id="pattern0_1393_1239" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1393_1239"
                                        transform="matrix(0.00742857 0 0 0.01 0.128571 0)" />
                                </pattern>
                                <image id="image0_1393_1239" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB8klEQVR4nO2dsU0DARAEv9G9LkxMSOAGEJRABS9BLXRi9MFLhJbxaweYkT4De9i9JQHEsoiIiIj8Zeb14XL00/4afxVjISz2Qk5vT5fzx/Pdnu31XMgN7KFtIa6f73d7ttezkBuwEBjjQlhYCIxxISwsBMa4EBYWAmNcCAsLgTEuhIWFwBgXwsJCYIwLYWEhMIa0EH98uVgIjSEu5D//PHmIhSBkSqAyQMmUQGWAkimBygAlUwKVAUqmBCoDlEwJVAYomRKoDFAyJVAZoGRKoDJAyZRAZYCSKYHKACVTApUBSqYEKgOUTAlUBiiZEqgMUDIlUBmgZEqgMkDJlEBlgJIpgcoAJVMClQFKpgQqA5RMCVQGbZm8nB73jzvq2d6DnAFKJhbCLeR8x1/U+/7+LuTGQtY7HsT2WMgPF7JaCOtb1mohFkI4SpRMXIiF0I4SJRMXYiG0o0TJxIVYCO0oUTJxIRZCO0qUTFyIhdCOEiUTF2IhtKNEycSFWAjtKFEycSEWQjtKlExciIXQjhIlExdiIbSjRMnEhVgI7ShRMnEhFkI7SpRMXIiF0I4SJRMXYiG0o0TJxIVYCO0oUTJxIRZCO0qUTFyIhdCOEiUTF8L6hy4B/Z064p/a7J9w5HNtIXPQc20hrQxQMrEQERERWX45X3mFX7Fsubh8AAAAAElFTkSuQmCC" />
                            </defs>
                        </svg>
                    </div>
                    <span class="text-xl font-bold ms-3">Dashboard</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('customer.pengajuan.daftar')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-green-500">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center">
                        <svg width="49" height="42" viewBox="0 0 49 42" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect y="0.5" width="49" height="41" fill="url(#pattern0_1524_3527)" />
                            <defs>
                                <pattern id="pattern0_1524_3527" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1524_3527"
                                        transform="matrix(0.00836735 0 0 0.01 0.0816327 0)" />
                                </pattern>
                                <image id="image0_1524_3527" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,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" />
                            </defs>
                        </svg>
                    </div>
                    <span class="text-xl font-bold ms-3">Pengajuan Uji Sample</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('customer.hasil_uji.index')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-green-500">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center">
                        <svg width="44" height="42" viewBox="0 0 44 42" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="44" height="42" fill="url(#pattern0_1524_3530)" />
                            <defs>
                                <pattern id="pattern0_1524_3530" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1524_3530"
                                        transform="matrix(0.00954545 0 0 0.01 0.0227273 0)" />
                                </pattern>
                                <image id="image0_1524_3530" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFeUlEQVR4nO2dW0wcZRTHp/Lo5cUHE1NfTHxUY2K9PCiNOwNpjG20rDKz1KTMUE2bJm3qrUkFIY2mKTBDIfAAoSwzUOmyU5poWzUFtVItLdZ4LzVFkQJSSeUi7LbCZ77NomWZ2csM7JydPb/k/wKzs+f7fjs7EzjJYZgMRVGD6xRVr5NV/SdFC85E8yP9WbU/8KjT9WUNNeqJu2RVPyKrwQVF04lR6O9kVW870NR1p9P1uppav363rOnfmYlYFjX4LX2N03W7krKystsULdiTtIz/c5q+1un6XUeN1rll+VeTPq6o+ltym/5kJJq+V1GD15ZL6RScrt91yKr+9RIZWvBKjRpcG3tc5ZFj9ylqcDDmnnLBmapdSo0aXBv7qa9R9Tyz42VN3xB7k69t77o3vVW7GFk9tn7pzVqfiHdfiN5vri8R2KbnMtmK1+vN8fjE9awgVbC8eJgTpKNG2f2u/MM+pfH3RKloaPnz1s2t8gfCiWqoagmEb31NRYP/WjLvRWsyqze6lnKWL8mla2QyAQ9fspnlpcucIJFEeftQY6pPTCR6hZCy6vr7zWp4/UDDA5bOq+mRmpKpnRPEAc4nPc8AZg3LS9XJLcamEE0n+xvUS2aF7G9o/WX1hUTDS5V07Qw0OF58J6WF2BSiaDopr2vuia2jvN7/mZ1zpixEkAjLi6UMJDyFxY+wvDifbiFK5H7SOf9eY/sfNNWtgXm757MoZD6vaOvDDBRYQTqW6iJWSoiywrEiJCpFZyCQ691+B8dLc0ZF5hdtI2VVdURuajWM0hpwXIASm9aAab2llbWRNRkKEcTQxuJi5/+YyfqkJ8w+NZ9+eZ7Eo/OTz50XoC0NrSkePWf7TK+SPF58zGkfjEeQNhkV99zWHWRhYSGBkDMAhZyJWzNdE12b0ZrpXjjtg+EE0WtUXMGru0kiTn1x3nEBSkxO9V5IWDddm/FVInozWsiV4VHHBSgxGbw6mr1CKKfPXXRcghJNd983SdXsaiGUgV+HyfGes6T9RLcjoe898Ntw0vW6XkimUYBCYIFCgIFCgIFCgIFCgIFCgIFCgIFCgIFCgIFCgIFCgIFC0sTw5DAJ/xNOeBwKSQNDfw0Rb0cheePjvSSUQAoKsUHoZoiMz4wnJcPTkh9JIikoxIaM1z56k/CBLWRkasTwmMHrg2Tz+y/9J2MxTf3N2SOk/vINUv592HYO/nwjoYzFDTaSEntlLGbXyT1k9uZs9gh58OQsyemYsZ17uv42fY9DX9Ut22hf58tkdHrMlgwU0mFNyHR4mmz/YOeyDadXSv/VfssyUEiHNSHxpHD+DZZluFLInoth8mJvyHZK+kIJN89MilUZrhSSbqZCU6S4q8RQxs4Pd0UeAFIBhawAY9Nj5Flt0xIZG9tfIBNzEymfC4WsghSrMigoZAUZmRwh0vFXyOTcpOVzoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoJA0gY1ygBjCRrn0EMJGOTj/wg1hoxw2yjFOg41yUuZ3naSjc3EaG+VgCaFgoxw2ynmd/sbCRjnBBfcQJxjDRjlYQijYKAeQEWyUcx8F2P0OCxQCDBQCDBQCDBQCDBQCDBQCDBQCDBQCDBQCDBQCDNBC7Iw8ykQWwI88Ktz2uPlQsD7iNrp7z5kOBfP4StaBH5tHR82ZjaHLtJTGGZvHCeIsV1R0OwMBOlTR7FOTNeGlIAOFZwrFh6yMXnVLWF6cp+NnGUjQAb1ObwznXPYxAFnDCmIVgM0haQ0vHQQ5vnsROvQ9Mvzd6Y0SVjviJRCPucng9XpzOKH4aTpjneXFw5wgHXVJmuma8n3SU3SNTu8zgiAIgiAIgiAIswL8C14t80cmVfdkAAAAAElFTkSuQmCC" />
                            </defs>
                        </svg>
                    </div>
                    <span class="text-xl font-bold ms-3">Hasil Uji Lab</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('customer.jadwal.index')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-green-500">
                    <div clas="flex-shrink-0 w-8 h-8 flex items-center justify-center">
                        <svg width="34" height="35" viewBox="0 0 27 28" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="27" height="28" fill="url(#pattern0_741_1283)" />
                            <defs>
                                <pattern id="pattern0_741_1283" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_741_1283"
                                        transform="matrix(0.01 0 0 0.00964286 0 0.0178571)" />
                                </pattern>
                                <image id="image0_741_1283" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAANZElEQVR4nO1da0xcxxUeNZWaH/nTSq3URv3TH21/VU2bH1WlNpHSVKqa9KGUtHZ5s+AFr4F979614jiu5Vca17bixO84GOPgV2wIDwfbQgS/vdjLEoxtWIxNbBbYBfNcMJzqG3HRGt9dYO/ie/HeI30SZmfmzJxv5syZM7OYMRnicDi+KwjC3wVB+MDlclUJgtDqcrkCLpfrkSAII4Ig9DqdTq8gCEddLte7Lpfr1RUrVnxHjk5NZkhSUtJzTqfzbUEQvnA6neMul4tWrlw5K1AOEAShz+l07nW5XL/VjCtDVq1a9S2bzZbtcDhaBUGYEwnRyEEbDoej1m63vyqnXwkpVqv1Vw6H47IUEevXr6fS0lKqr6+nlpYW6uzspEAgQN3d3XTv3j3yer109uxZ+uSTT+i99957or4gCJN2u/2A0+n8vtLjXBRisVjSHQ7HsOhygHfeeYcOHz5Mra2tNDExQZOTk3NCKBSia9eu0e7du6fbEleL3W7vtFqtryg9XlXvFXa7fafT6Zw2HGY0VkNPTw8nQg7a2tpo+/btjxHjcDgewS0qPXa1knE4nAy4plu3bskmIhyPHj2ir776iq+4MFImbTabQWkbqEosFstuh8PBXQnw8ccfU39/PzfgQuDOnTu0bt26aX1Wq3XCZDL9W2k7qEIsFkuh3W6fNs6+fftoeHiYxsfHFxR+v582btw4rddms41ardaXWaJHUzBE+Mp4GmSIePDgAa1du5brhru0WCy38vLyXmCJes4wmUwX4apgDBimt7eXxsbGnipu3rzJ9xL0ASvVZDJtZIkoZrM5x2azcUNght64cYOHqUqgoqKC9wMwm80hk8n0U5ZoUZXJZLotGqGkpOSpEuDxeDgJo6Oj/N+Dg4O0YcOG6VViNBr3sUQSo9H4L4vFgpCTuwv4chhnoREMBmnHjh20dOlSjgdhei9evMj7A5jN5rHCwsIfskSRwsLCKsxEDP7gwYM0MjKy4IDBly9fTkuWLKH09HSqqal57POhoSEeCqNPVqsVe4mNJYIYjcbvGY3GcXE2Njc3RzUk8lVIfyD6ioUIBAqI3kAEAJ0+n0+ybEVFxfQqESzZdz37mel2CSvoPsmWP6xiuWrBQCV7e7CSxWcF5+fnv2U2m7mvXrNmDZ+ZMLYUkLtKTk7mhoRru379esSyUrhw4QLl5eXx+nBRe/bsoYcPH0Ys397ezvsFmE2F5N79PPlKGAXKGI3VqAehLxkNVrGh/kqWEg9CtsAlYNBFRUWckEhA5AVDirMb2LRpE89LRauHvWHr1q3TdZYtW8ZdVrQ6Q0NDfHPHJOGEmM1Ute0n1H6IUV85IzqrPgxUsiHZK8VgMNQg3AXgx2czEtLsBQUFj5GCVYPN+f79+0+Ur62tpezs7OmyOI3jVD6bnqEpIDOMvoGQ4vUvU+sBRr2fM5qsYUSn1YVQNaOHZewfcldIm0hIQ0MDn5WzAXkt+HfM9HBisDnDgGVlZVReXs5Xj/hZWloanThxggYGBuakY3AKx48f531DFLj93dfp1n5G3UcYTZxiRF+qC6FKRg9PsCy5hPSJhCCbC4PNFZjp+/fv58aG0TMyMnjmVgQOmHBxmN1wd/Npe2AKp06d4n2DW9288g1q2cvIf5jReCUjOqUuhCoYBY8ynVxCxjBYAFnXWIyGetgj9Ho9N344tmzZwm8QY2l3YGCAuzyxf+sdf6OWPYy6DjEa/4IRVakLoXJGwVKZhBgMhlFxwAg/EfXEir179/IoKhxut1tWm2fOnJkmZIPjr9Syi1HXQUbjZYyoQl0InYwPIT3igHEGwf4QK+7evUum/HzuugCkP/r6+mS1WVlZyfuGPeQD4U/UsoORv5jR+AlGVK4ujB5nFDwkk5Dc3NxmDBZAKCrHeEDgWBFd/ONLdPHsGdlk9Pf387wa+mYymeijla/QrZ2MuosZTZxkRGXqwugxRsFimYTk5eWdhK/HoBEdwYhyMLLlXRr/5+9kt9M3hW3btvG+FRYW0qE1v6DWHYx6ixlNfs6ITqgLo0fiQEhubu5aDJaHldu384SfHIQKl1JopV52O8FgkD+mmLqoovz8fKrZ+CK172TUV8yIjqsPo6WMgkUyCcnOzv7DihUreESEnBHeU8VsxECAJt76DQ3v2xoXQhoaGqajtfzl2dS09Tm6u4vRwEFGdEx9GP0sDoSkpaU9r9fr+8WB19XV8TA1FvQ1N9LkG7+kgdrqmNsIhOHTTz/lfcL+sc78Z2rZyujBbkYjJYzoiPowWsLIX/XmR7IImVolRXBbGDzOE8jIxoKBymM0+ZdfU8DXGnMbvVPAC8ipG0PCCj6++mfUuo1Rzx5GE58xolL1YbQYhCTJv0zLzMz8Pc4MmIkA0uuxGHFk13/pke4N2WT09vbylInYn/y8dLq84dvUsoXRg12MBovUid59jO6WxYEQiE6nq0fS0Gg08vMD0iLYVOeDsVUGCv3HPO96M9HW1iZe3ZLBYKBtmwSqL/8fNdbupA53Md1v/EyVaL9ygFoulFjiQkhmZuZrer1+EkYAkDzEBj9n+P00mf0mDRV9NL96M+D3++nDDz/kfYAbhbvCaf3KlSv8cgwZZbUC9zc3btyQt6mHi06nK8WMFPcTXEDN1ZCBliai5Neov/aULELKy8u5fiA3N5enY5Dyb2xs5DmzhCJEr9f/ICsr6z5cFwwiPgfCrJ0N/ae/IEp9nXpab86pvBTq6+v5ngHdmBjIGOPtL/JheK+ltMHnQojX643vY3G4Lp1ONwZSABgFOa6urq6oCNadptA626zlIgHhttls5jrhpnAQPH36NF26dIm+/vprnidLSEIg6enpmTqdbkIkBeEnDBOrsaMB17tI2WDPKCgo4EQgjY/LLNzB4ws/GCjKqR0LRggkLS3NnJWVNQEDATyXdOgQ/1ZUvAaACzGce0QdBoOBk3H06FE6d+4cf0CHhxVKG1oVhEyRkp6ZmTkKQ4lGgwvDLR4ObrEubdy74Is/PCUy1S7OQQaDga8WkIGA4vbt24obWVWEQFJSUl5KTU29iftz+HYReAaEFQPDffPNN7OSAALPnz/Pn/6Ip28AJOARBNqrqanhbkokA/WUNrLqCIEkJSW9kJqauiEjIyOEUDScGABJSVzVHjhwgJ+wcYYB4Hrw/ZL333+fR08z64HknJwcnmVGNHXp0iUe3sJNzSQDhOLAiNB3rgZCWdRB3bmUj0WHIoSIkpaW9vPk5OTi9PT0cRgST0Exw+cDuCadTseJ2Lx5Mz/0wUUhs9vc3Cy5gYMgkIULNACpHazKSIbBZ1hlYnnUnW0vmq8OVRAiSmpq6ospKSmunJyce7iqhYEx27F6YHAQBeBn/A7Gz8rK4sBqwmEPjxdAxNWrV3kkBRfV2dn5RBQGo4QbSkS0UByfzSyPNtCWVPlYdEgBK8vr9eYwpaS6ujq/urqauypES6tXr+bf1MUdOJ7u4Gd88QcuCV+jxlkDJGCwWBEgAgc+nDEiDdLn8z1hKLF+pDr4TKoO2oqXDlUSUldXtxwb9eXLl/lmDGNLAYNDLgoDxB6B0z9WRDQiuqbQ0dEhaSyExJHq4DOpOmgrXjpUScjVq1f1TU1NPOkHI+NEjVkfDvwOn2ElwE9j8JFcR1cEhO8HIjD4SOXhy2eWRxvx1BFJr6KEQDk6gc6IoS0OjuEQzyvzGVjXDKA+/DlWGGatqHM246CsGCzM1odYdKiaEA1d6iAEyzrW7O6zhjtK7yEaIX71EYJlqvTM9KsEqnBZGiF+jRC/ClaDalcI/OZ8781RBwdEhJg4oyBCi1a+q6uLlxMPlnPR+TR0SOlcdISgvNShLVqd6xEObUrqUCUhDQ0Ny+bbacw+qTQF0ijdEuVxuIyU1lBSRyRCYJNFRUikxB/8b7dEeak0iJj4U1LHM0MI/LRUahz3Cd0S5fF7qdQ42lFSh2oJiTTrImFmIg+GQPo7Wh2fz/eYwcREoZI6Iq00RQnxeDx8hcTyfhd+W8ymzqV811R6G/XUpCMcqAubLEpCnkXc0QjpUZwEjZAe5Q2vEdKjvLEXDSGILJQ2RI9KMHVLqfymHut3CHG/jqTcXMr7/X5eHvUWSodcqGKFzJcQxOvhr0IQ+2NmRavT3t7+2BlBTGnEU0fCEoIXKlKn6Eiz2O/3S56i0U68dCQ0IXiiGenNVK9E+UhvpqJ9U3i+OhKakEiZ2Eh7Q2dnp2R5tBMvHc8MIV6vdxlm3Xz+OoNUqhv+Plodj8RLRPG/VoqXjngAtoBNFhUhosEwi+Fa4O8RMkYr39PTM/33glEP9eOt45kg5Nq1a3os04UeaGCRALaATTRCAsqToRESUJ4AjZCA8kZXPSHYyOLxx8qeBXR0dChLiNvtzsWsUNoQQZUAtoBNNEKCypOhERJUngCNkKDyRlc7IUvwfEZpQwRVAtgCNlGMkMbGxh83NTUNozPx+sPJfYsUsAFs0dzc/COmpHg8nkyv1zuMCyCEfYkIn8+HHNawx+PJYGoQzAq3273U7XYXJCiWKr4yNNFEE0000UQTTTTRRBNNNNFEE0000UQTTTTRRBMWQf4PcvDWeSF6UpcAAAAASUVORK5CYII=" />
                            </defs>
                        </svg>
                    </div>
                    <span class="text-xl font-bold ms-3">Jadwal</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('customer.profile')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-green-500">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center">
                        <svg width="35" height="35" viewBox="0 0 100 100" fill="none"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="100" height="100" fill="url(#pattern0_819_1410)" />
                            <defs>
                                <pattern id="pattern0_819_1410" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_819_1410" transform="scale(0.01)" />
                                </pattern>
                                <image id="image0_819_1410" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFhklEQVR4nO1dS2icVRT+QCOaVbtoJlG0mKSrBBKhAWPEhTVNpe4aMzmDj4UP0D6CgsWVDxDFJ+5UqmitaC3ia1nRqLjoStRurIquLG1FULqoC+svB85ACEyS+e+95557537wQUhm/jmPzH//e14XKCgoKCgoKCgoKCgoKFgL/QAGAWwDMA5gEsAUgBuEU/K7cXnNoLynwAMuBTAkBp4DcDsAqkl+70651pBcu2AD6AMwAmAHgKaDA9YjX/tm+Sz+zIJVaACYAbAQ0AmduCC3O5ah58H3+NkITqAO3AXgml70Cv833mrAAbSGYwbQA7gcwLQBg9MGeVPOT2mjAOYNGJm65Lws/tmgTxZNSpw3ArgMiWMzgNsMGJM8kXXZhIQX7hRvUbSBTSZvLpPC1sAbO4rMpuiYBDiGtGjAaBSYi/KgYhpbe8QZtILXwvCakfNtijqwaXFN2ewYjU2d85aevjiUvduAUcjAI7GJyHGMUMgSgCMAfgBwGsA/Qv75e/nbUgS5OGIdFaPKCj8M4ASA/wBUG+BJAAeVZRyJGSjU3Pi9A+DfDTpiJfk9byvKyTa5IoZDtOJTLQDLNRyxmstyLQ2Z+TauigHF/7hPPTijzY8V5VbNQGoll1726IxK1p6XFJNcKrhKSaE7AZz17BDmOQB3KelwpYZDtHLghwM4o803lXRgWwVFQ0kRAvBLQIf8nMtaMqOkxN4u9hp115K9Srrw02gQ9CnWTb0Q0BltPqeky0KokMqI4tf8dQWHHFLUZziEQ3YoKnBUwSFHFfXhslXvEV3NXMehzL4hTd8F3kOKwhOA5xUc8qyyTlw26w2TysI/qPCU9YCyThM+HTKnLDwF3of8FEEf7k/xhhjp2bcy2KmvJNvQC/ojCE8SyzqTeCxrNb3kSQYjCU8SmfW5lvC1XoyoT8NX4RtF5CceHfJRZF28FNaNR1ai5Slj+IVixrATx3w45LrISpDwiENO/bAB+Um2D86YMqAICfcD+LqLdeVbAA8ZkLvN7T4cYrEF7YBUknAN1u8ALgj55+/kG3HAgJxBih8sOoQS5XRutyxKnNtzWtQpA07m8NhLGXEsh40hZcTR1EMnlBkbKQcXKUN6K8Lu5e4o8lgV7w07AwnJsaXHAbwH4DPp/YhB/ux3ATwWMN41azmFy0q/ESjfUTnyjBRAtCyncH0WOXAu+5QBw1fr8EfPefdBi2VA+yVjVyXCcwD2WSwDghR7uQh1N4DfDBi56pK/ekj5ei+U81FK+oEB41Y1ecxiKalLsfU90rZcJcoLooOpYmuXdoRXDRi1cuQr1toRXBp27gXwhwGjVjX5J4D7a+oefJjmLTUFexLARQPGrbokp4ufstrSBmlkrLu4HTNg4KpLvu+gr9qUoF01BWxJmKJKhMcdnMH10GrY4iAoO+VLA8au1uE3juET9UHMLsUPdwD4yoDRqw5cFhnr6nc9Ig2f2eMgNEm5jqWF/qJEfZMcPuOrEZS7Yf824Iy/PHVVBdmVa9dt8S7488BdU2vxhMM+Q20TGGPE3zPKAUgOHD6d24g/yABIn2neJ2QaXChHnJLG0laOQzBDjol9VHo6TntwAl/jwwAj/5q+k0+pDFI+COA12azx8Jjzaxj/vLzmuLznkUAyLaZwIs+o4nTrFoD7ZALpkvys1ZSTxKjxNq6OdMAXKTGpYfxtlOMqDGJTZge67Lb4NNUt+jJp+pnO7YTQkURP3NljIRwSCqkdmzcjMmePAYckFylwTnI+PYctHgrwyCNnZS5xz6MhkdKFiIcT98RRq3WeyIblW6NxfPewpQhtKgfcT0h/iks0eV5uRxMSCLwktnK5oF8iANuko3VC+uinhVPyuzF5TSNmOrWgoKCgoKCgoKCgoAAJ4H8ahZ/pgeHzkAAAAABJRU5ErkJggg==" />
                            </defs>
                        </svg>
                    </div>
                    <span class="text-xl font-bold ms-3">Profile</span>
                    </Link>
                </li>
            </ul>
            <div class="absolute bottom-0 left-0 w-full p-4">
                <Link :href="route('dashboard')"
                    class="flex items-center p-2 text-white rounded-lg hover:bg-green-500 dark:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                <span class="text-xl font-bold ms-3">Keluar</span>
                </Link>
            </div>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div>
            <!-- Slot untuk konten -->
            <slot></slot>
        </div>
    </div>
</template>