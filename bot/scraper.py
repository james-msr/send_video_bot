import instaloader
from instaloader import Post
import json
import os


class InstagramLoader:

    def __init__(self, profile: str) -> None:
        self.videos = []
        self.profile = profile
        self.keys = {}
        self.last_key = self.get_last_key()
        self.loader = instaloader.Instaloader()

    def get_posts(self) -> None:
        posts = instaloader.Profile.from_username(self.loader.context, self.profile).get_posts()
        last_key = None
        for post in posts:
            if post.shortcode == self.last_key:
                break
            self.get_post(post)
            if last_key is None:
                last_key: str = post.shortcode
        self.update_last_key(last_key)

    def get_post(self, post: Post) -> None:
        if post.is_video:
            self.videos.append({
                'url': post.video_url,
                'caption': post.caption
            })

    def get_videos(self) -> list:
        self.get_posts()
        return self.videos

    def get_last_key(self) -> str | None:
        last_key = None
        if os.path.exists('/var/www/domains/send_video_bot/bot/keys.json'):
            with open('/var/www/domains/send_video_bot/bot/keys.json', 'r') as file:
                self.keys = json.load(file)
                last_key = self.keys[self.profile]
        return last_key

    def update_last_key(self, last_key: str | None) -> None:
        if last_key is not None:
            self.last_key = last_key
            self.keys[self.profile] = last_key
            keys = json.dumps(self.keys)
            with open('/var/www/domains/send_video_bot/bot/keys.json', 'w') as file:
                file.write(keys)
