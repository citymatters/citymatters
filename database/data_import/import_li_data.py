from os.path import isfile, join
from os import listdir
import pandas as pd
import json
import numpy as np
import urllib.request, urllib.parse


PATH = "../luftdaten-info/"

FILES = [join(PATH,file) for file in listdir(PATH) if isfile(join(PATH, file)) and file[0] != "."]

PM_SENSOR_TYPES = ["PMS3003", "PMS5003", "PMS7003", "SDS011", "PPD42NS", "HPM"]
TEMP_HUM_SENSOR_TYPES = ["HTU21D", "DHT22"]
CITY_MATTERS_POST_URL = "https://smartcountryhacks.citymatters.de/api/add"


def create_json(row):
    return {
            "sensor": "LI-{}".format(row["sensor_id"][0]),
            "lat": row["lat"][0],
            "lon": row["lon"][0],
            "alt": None,
            "datetime": np.asscalar(np.datetime64(row["timestamp"][0] + np.timedelta64(30, 'D')).astype('datetime64[s]').astype('int')),
            "data": [
                {
                "type": "temperature",
                "unit": "celsius",
                "value": row["temperature"][0]
                },
                {
                    "type": "humidity",
                    "unit": "rh",
                    "value": row["humidity"][0]
                },
                {
                    "type": "pm2",
                    "unit": "µg/m3",
                    "value": row["P2"][0]
                },
                {
                    "type": "pm10",
                    "unit": "µg/m3",
                    "value": row["P1"][0]
                }
            ]
        }


def push_json(json_data):
    try:
        json_params = json.dumps(json_data).encode('utf8')
        req = urllib.request.Request(CITY_MATTERS_POST_URL, data=json_params,
                                     headers={'content-type': 'application/json'})
        response = urllib.request.urlopen(req)
        print("HTTP response:")
        print(response.read().decode('utf-8'))
    except Exception as e:
        print("failed to push data because of: {}".format(e))


if __name__ == "__main__":

    for curFile in FILES:
        reader = pd.read_csv('../luftdaten-info/2018-10_sds011.csv', delimiter=';', parse_dates=['timestamp'])
        for row in reader:
            final = pd.DataFrame(
                columns=['sensor_id', 'lat', 'lon', 'timestamp', 'P1', 'P2', 'temperature', 'humidity'])
            if set(row["sensor_type"]).pop() in PM_SENSOR_TYPES:
                pm_df = row[['sensor_id', 'lat', 'lon', 'timestamp', 'P1', 'P2']]
            elif set(row["sensor_type"]).pop() in TEMP_HUM_SENSOR_TYPES:
                temp_hum = row[['sensor_id', 'lat', 'lon', 'timestamp', 'temperature', 'humidity']]

            final = pd.concat([final, pm_df, temp_hum], sort=False, ignore_index=True)
            final = final.where((pd.notnull(final)), None)
            #push_json(create_json(final))

