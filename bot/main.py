import sys
import json
from scraper import InstagramLoader


def main():
    profile = sys.argv[1]
    loader = InstagramLoader(profile)
    videos: list = loader.get_videos()
    data = json.dumps(videos)
    with open('/var/www/domains/send_video_bot/bot/data.json', 'w') as data_file:
        data_file.write(data)


if __name__ == '__main__':
    main()
