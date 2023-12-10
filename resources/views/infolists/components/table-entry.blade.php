{{-- <x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry"> --}}
<div class="w-full">

    @if ($getTableTitle())
    <div class="py-3">
        <p class="text-lg">{{ $getTableTitle() }}</p>
    </div>
    @endif
    <table class="w-full">

        <thead>
            <tr>
                @foreach ($getChildComponentContainer()->getComponents() as $item)
                    <th class="text-left p-1 border font-normal text-sm bg-gray-100 dark:bg-gray-800 dark:border-gray-700">
                        {{ $item->getLabel() }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($getChildComponentContainers() as $item)
                <tr>
                    @foreach ($item->getComponents() as $component)
                        <td class="p-2 text-xs border dark:border-gray-700">
                            @php
                                $component->label('')->size(Filament\Infolists\Components\TextEntry\TextEntrySize::ExtraSmall);
                            @endphp
                            {{ $component }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>
{{-- </x-dynamic-component> --}}
