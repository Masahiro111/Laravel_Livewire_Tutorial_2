<div>
    <nav class="bg-gray-900 px-3 py-2 flex items-center shadow-lg">
        <div class="bg-red-500">
            <button class="block h-8 mr-3 text-gray-400 items-center">

            </button>
        </div>
        <div class="h-12 w-full flex items-center">
            <a href="{{url('')}}" class="w-full">
                <img class="h-8" src="{{url('/img/logo.svg')}}" alt=""></a>
        </div>
        <div class="flex justify-items-end">
            <ul class="text-gray-200 text-xs">
                <a href="">
                    <li class="cursor-pointer px-4 py-2 hover:underline">Login</li>
                </a>
            </ul>
        </div>
    </nav>
    <div>
        <aside>

        </aside>
    </div>
    <main>
        <section>
            <h1>{{ $title }}</h1>
            <article>
                {{ $content }}
            </article>
        </section>
    </main>
</div>