<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ theoo.dev ]]></title>
        <link><![CDATA[ https://theoo.dev/feed ]]></link>
        <description><![CDATA[ All the things I think and like to share ]]></description>
        <language>en</language>
        <pubDate>{{ now()->toRssString() }}</pubDate>

        @foreach($notes as $note)
            <item>
                <title>{{ $note->title }}</title>
                <link>{{ route('notes.note', ['note' => $note]) }}</link>
                <description><![CDATA[{!! str()->markdown($note->content) !!}]]></description>
                <author>Théoo</author>
                <guid>{{ $note->id }}</guid>
            </item>
        @endforeach
    </channel>
</rss>
