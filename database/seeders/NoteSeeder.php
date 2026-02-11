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
            'content' => <<<EOF
            Hey there,

            Lately, my algorithms have been feeding me a lot of "chronically offline" content.
            You know, this is content where people swap their smartphone for a [dumbphone](https://en.wikipedia.org/wiki/Feature_phone), they delete social media apps, they read books, they use an iPod to listen to music, they practise outside activities, etc.
            And like, this is really appealing to me, I love the idea that as a society we are slowly falling out of love with the online world and coming back to the real one.

            But I'm afraid that all this talk about it online is going to make us miss the point. Like what if it's just another trend?
            I feel like if people are going offline, it shouldn't get posted on social media as much. If you deleted social media apps why would you post on them, right?

            And maybe this feeling is because what we are seeing online is only the most exacerbated part of it, and there are probably hundreds of people doing their thing in their little corner of the planet without needing to showcase it to the world.
            But I would still like to discuss what a healthier way of being chronically offline might be.

            So let's start by the dumbphone/iPod thing. At first, I was so into this, I was looking everywhere on the internet to get a second hand iPod for cheap and considering swapping my iPhone for one of my Grandfather's old phones.
            But the more I thought about it, the more I realised that this was mostly driven by 90s/2000s nostalgia (a time that I didn't even get to really experience myself) and that I was probably much better of keeping my iPhone.
            Texts and calls have improved so much since then and that's what I would do if I had a dumbphone, so might as well do it on my iPhone where I can keep sending pictures, links etc.
            And I totally agree that texting from a dumbphone looks so much cooler. Like if I saw a young person doing it, I'd think that they're interesting, but it's in no way a practical thing to do.

            And for the iPod bit, OMG would I want one. Apple really cooked when they designed that thing, I never had one as a kid nor did anybody in my entourage; but I so wish they did.
            But then again it's like, my iPhone can do music superbly well even without streaming services.
            iTunes is still a thing and I could also buy music on Bandcamp and many CDs/Vinyls now offer a QR code to download a digital version of the album.
            And even if I'm not a fan of the apple music app, there are plenty of alternatives on the App Store.

            Now you might be like "Ok sure but what's the fun in that" and I agree with you, it is so much funnier to go back to separate devices for doing things rather than to use the pocket slab.
            But I think it's worth considering if you think you can keep it up in the long run. For me the answer is a big no.
            For example, I got a cheap but cool camera that takes pictures exactly like I want them to look, and I still am barely using it because I don't think about taking it with me and because it's less practical than my phone.

            So for the people like me who are keeping their iPhone, what they do is delete social media apps. And I so agree with this.
            Like personally, I haven't had social media apps on my phone in years. I can still go on them on my computer when I want to and tbh I've never really jumped on the TikTok/Reels wagon, short form scares me because it's too addictive.
            But I think there is something quite sad about social medias nowadays (and for quite some time already) in that they have all become places to consume content.

            We could call that the "YouTubification" of the other platforms, they became places where you go to distract yourself instead of being places you go to keep in touch with your peers.
            But I remember when social media was about your friends, and it was a blast! You'd just post whatever you wanted and your friends would leave comments and you'd comment your friends' stuff etc.
            Like maybe you remember how cool Snapchat stories were in the beginning, you could just snap a picture of whatever you were doing, slap some text on top and your friends would reply to it.

            It's just that I feel like the problem with social medias isn't the social media itself, but rather the fact that there is an algorithm trying to optimise the content you see.
            I feel that a social media that would ONLY display the stuff you chose to follow that you could only discover organically or through friends (since there would be no algorithm to make you discover things), would be so so great!

            I don't know of an app like that that exists rn and the attempts I've seen here and there didn't quite have the reach of an Instagram or Facebook where literally everyone was on it.
            But I hope that someone will build a new platform like this, maybe even a paid one like [Are.na](https://www.are.na/), where people will just go to interact with their friends.
            And like, another thing that is in this same department is making a website. Maybe we'll see a wave of people building their own little place on the internet where they can share stuff. Gotta say I really like [blot](https://blot.im) personally cause I think it's super approachable, you don't even need to learn markdown!

            For the other things like reading books, going outside, talking to people IRL etc, I obviously think this is fantastic, ain't nothing more to add.
            My issues are only really about this seemingly complete rejection of online life which I think is an awesome thing if done right.

            [What do you think](mailto:hello@theoo.dev)?

            Have a wonderful day, byeeeeeee
            EOF,
            'published_at' => now(),
        ]);
    }
}
