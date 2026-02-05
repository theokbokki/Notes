<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Note::create([
            'title' => 'An ode to plaintext',
            'content'=> <<<EOF
            <p>I consider myself a simple boring guy, I like ed, I enjoy eating plain pasta, I wear only a few clothes in rotation, and my favourite way of writing and reading text is plaintext.</p>

            <p>There is something appealing about <code>.txt</code> files for me, it's only characters that all look mostly the same, none are bold, none are bigger, none have a background. <br />
            Plaintext works everywhere, every computer can render it, it's fast, it's quiet, it's most likely futureproof, it's portable, I love it.</p>

            <p>If I could, I would have everything written in plaintext. It's limitations make it predictable. It doesn't have links or blockquotes, it doesn't render code with fancy colors nor does it bolden or italicize text. <br />
            I might make an exception for images, but other than that, plaintext is all I want.</p>

            <p>But despite it's seemingly perfect simpleness, plaintext has one big flaw for me, it doesn't work quite well on the web. <br />
            I mean, sure your browser can most likely natively render it (in a superb way may I had), and most users will be able to read it with not problems. But that's not how accessibility works, you can't accept 'most users'.</p>

            <p>Web pages rely on semantics to convey meaning. Users with assistive technology can jump to headings and links, are warned when entering a list and know how many items are in it, etc. <br />
            So if you serve them some plaintext, they will only hear a big blob of text being read, with no way to differentiate between different parts of the text.</p>

            <p>And for that people have invented markdown. It's still kinda plaintext, but with some light syntax added so that you can transform it into HTML. <br />
            This way, your users get all the benefits of HTML and you only had to write some slightly more verbose plaintext. <br />
            But generally, markdown doesn't look like plaintext once rendered, titles are bigger, blockquotes are well... quoted, code is rendered in fancy code blocks, lists have a dot in front of them etc.</p>

            <p>And that's why I've written <a href="https://github.com/theokbokki/txt-css">a small CSS file</a> that takes nice semantic HTML and makes it look like 'enhanced' plaintext!
            You still get your links, your quotes and your images, but the overall vibe is plaintext.</p>

            <p>It was deliberately inspired by the default style browsers apply to plaintext as well as the ways man files format their text to look legible. (I'm a huge fan of man files, but that's a story for another time).</p>

            <p>If you decide to use it for something, don't hesitate to send me <a href="mailto:hello@theoo.dev">a little email</a> ^^ I'll be super pleased to look at what you wrote with it.</p>
            EOF
        ]);
    }
}
