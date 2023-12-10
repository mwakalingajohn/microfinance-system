<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        {{$formatState($getState())}}
    </div>
</x-dynamic-component>
