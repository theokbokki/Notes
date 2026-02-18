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
            'title' => 'Browsers',
            'content' => <<<EOF
            Since I was born in the early 2000s, I've been around browsers most of my life. The first one was Firefox, it was the one installed on the family computer and I didn't even know there were other browsers at the time. To be fair, there was no difference between the browser, the search engine and internet in my mind.
            Then I discovered Internet Explorer while trying to kill the time at my grandparents'. I would play random flash games and watch let's plays of Mario Kart Wii on Youtube. At some point, Chrome replaced Firefox on the home computer which had since become a laptop, and would even replace my grandparents' trusty IE. For many years after that, all that existed was Chrome, maybe Safari for the odd times where I used a Mac at school or at some Aunt's place, but I knew no better.

            Somehow along the path of my life, I ended up studying web development, and that's when the problems started. Because you see, before that, a browser was just a box with tabs that allowed me to access the internet, nothing more, nothing less. But then it became a tool, and a very important one as well, I wanted it to look good since I would spend most of my days there, but also to be fast and support many features. So at first I downloaded the three main browsers, to be able to test my websites properly, Firefox, Chrome and Safari, and I started to have preferences.
            Safari was the sleek good looking one[^1], but so many websites would break when they encountered it and the dev tools sucked. Chrome was the web development beast, it didn't look too bad, its dev-tools were fantastic and it supported every feature under the sun, but it was run by Google... yuck. Firefox, was the ugly one, I hate how base Firefox looks, but at least Mozilla foundation felt less bad than Google or Apple and it supported most features with good enough dev-tools.
            I was torn, constantly switching between the three for different tasks, never feeling at home anywhere and I wanted it to stop. So I started looking online for alternatives, and there were many.

            That's when my browser frenzy got started, at some point I had 22 different browsers installed on my computer. There were the minimal ones like Min, the featureful ones like Vivaldi, Opera or Orion, the plethora of privacy focused forks, LibreWolf, Mullvad Browser, Waterfox, Ungoogled chromium, Brave, Throium,... a bunch of Vim inspired ones like Vieb or Qutebrowser. I even tried to use terminal based browsers like Lynx or w3m[^2] but quickly gave up on these. I was never satisfied, always looking for a good balance between privacy, my love for the keyboard and good looks. And that's when a new name started to rise discreetly, Arc.

            See, Arc definitely didn't tick the privacy nor the keyboard centric box, but it had such good looks[^3] that I couldn't keep looking away. I had it laying on my computer for about a year, using it on an off, never feeling quite at ease, but then it grew on me. At some point I had accepted that what mattered most to me was the way my browser looked[^4] and the ease with which I could use it to develop websites over the privacy aspect. And so I became a happy Arc user, there was little to complain about, after so many attempts and years of searching, I finally had found a browser that I would stick with in the long run.

            But then "The Browser Company" decided to stop working on Arc, and that sucked and this Arc breakup re-ignited my frenetic browser search. The novelty of Arc, with it's thursday updates had been able to keep my mind quiet, always having something new to try, but then I didn't have this safeguard anymore. So I went back to previous browsers I had really liked, Orion, LibreWolf and even Chrome... But the Arc way had poisoned my mind, not being able to open something in glance? No folders? No nice catchy animations absolutely everywhere? These had all become necessary things in my way of interacting with the internet.
            Fortunately, great folks decided to create [Zen](https://zen-browser.app/). This browser ticks most of these boxes _and_ it is also open source and more private. So I've been using it as my day to day browser for a while, but at the same time I'm still looking around for alternatives.
            The thing is, now that I'm not blinded by Arc anymore, I realize that I actually don't fancy the sidebar that much. It's alright, but I love the look of top tabs and the usability of them. And of course, Zen team has already said that they don't plan to support top tabs, ever. "The Browser Company"'s new browser, Dia, could have been a great replacement, but even if it ever reaches feature parity with Arc, I still don't trust "The Browser Company" anymore and I hate the AI and the monetary aspect of it.

            So here I am, stuck in this browser landscape, with so many choices but none that perfectly fits me. Maybe it's time I accept that nothing will ever be perfect, and to be at peace with using a browser that is more than good enough. To stop losing time switching and setting up different apps over and over again. I think that's a lesson for my life in general, stop wanting to always change things up, and be satisfied by whatever I have as long as it works.

            - [^1]: Sadly, apple killed that statement with their liquid glass update 🫠
            - [^2]: On which this webpage works really well btw.
            - [^3]: Debatable opinion, but as much as I dislike "The Browser Company", their designers do a pretty dang good job.
            - [^4]: Again, very debatable, I don't always feel comfortable with this opinion.
            EOF,
            'published_at' => now(),
        ]);

        sleep(1);

        Note::create([
            'title' => 'Teeth brushing',
            'content' => <<<EOF
            I love brushing my teeth.

            The feeling of the hairs of the brush scraping on each tooth removing bits of food and undesirable stuff. The frothing of the toothpaste filling up your mouth more and more as you brush. The clean and minty taste that tingles your tongue, nose and eyes.

            For me, it's a moment to reflect, a moment spent with myself in front of the bathroom mirror.
            A moment to prepare myself for the day ahead, to think about what I'm going to do, who I'm going to spend it with, what the weather will be like. It's generally the last moment before the day starts, 3 to 5 minutes after which I'm going to get engulfed in the movements of daily life.
            Then, it's also a moment to wind down in a way. When after a long day, I'm once again faced with myself. It means "you are soon going to bed", it's a time to think about the day that passed, how it went, who I met, how did I feel, how do I feel?

            Teeth brushing is a moment where no one will interrupt you. Because you are not going to walk around creating white sticky stains everywhere, because you are not going to talk and spit all over the other person's face, they wouldn't understand any of your gurgly sounds anyway.

            I wonder why I used to hate brushing my teeth.

            As a kid, I would do anything to avoid it. The toothpaste was stingy, it took precious time I could have used to play, it meant going to school, or going to bed, two activities my kid self despised heavily.
            Maybe as a kid, I was simply more in the present moment rather than thinking about what was and what will be. These 2 minutes tops I would spend roughly scratching my teeth were a moment not spent having fun, and what can a moment spent not having fun be worth?
            EOF,
            'published_at' => now(),
        ]);

        sleep(1);

        Note::create([
            'title' => 'Seeing life through whimsical glasses',
            'content' => <<<EOF
            Rose colored sunglasses on my nose, I walk in the rain waving to the dogs I cross path with that reply by looking back happily with their tongue sticking out and their tails wagging.
            I see a puddle, jump over it, almost fall in it then celebrate this new long jump world record.
            The rain has stopped and I keep looking at the sky hoping for a rainbow to appear, don't see the red light and almost get run over, this felt like playing crossy roads.

            Hear me out, life is the funniest thing ever, you just have to make it fun. The world around us is full of things that were designed for one purpose, but have so many more hidden ones.
            See a small fence? Jump over it.
            Follow pigeons around, yes they are slow, but they might take you to a random fun place.
            A bus stop pole can quickly become a fireman's pole or just a bar to spin around like you are in some sort of musical.
            Cartwheels are surprisingly has much fun as an adult as they were as a child. Don't believe me? Try it.
            Raindrops on the windows are like dozens of races happening at the same time, which one will get to the bottom first?
            Try to remember graffiti signatures and you'll find them in many different places, sometimes even in different countries.
            Wear clothes just because you want to, even though it looks awful and even you agree.

            The hard part is overcoming people looking at you weirdly, but that's ok, they are just not having as much fun as you are. And from my experience, none of them ever dares to confront you about it anyways, they must just think you are some sort of eccentric.
            A thing that helped me with this is to think about who I remember I saw today when walking outside. And the answer is pretty much no one, we just don't care about the people we cross path with. We might remember them for 10 minutes but then they go to the empty abyss of useless brain stuff.

            So please, next time you go out, put on your coolest pair of shades (yes even if it's not sunny) and have some fun. Just make sure you don't break or make anything dirty cause that's uncool.
            I would love to hear about how you are making your life fun, so don't hesitate to [tell me about it](mailto:hello@theoo.dev) and have a great day!
            EOF,
            'published_at' => now(),
        ]);
    }
}
