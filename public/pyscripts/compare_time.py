#!/usr/bin/env python
# coding: utf-8

import sys
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity

def compare_time(array):
    similarity_df = pd.read_json(array)
    similarity_df = similarity_df.transpose()
    minForRow = similarity_df.max(axis=0) #max for each row
    globalMin = minForRow.max();
    similarity_df = similarity_df.subtract(globalMin)
    cosine_sim = cosine_similarity(similarity_df)
    output_df = pd.DataFrame(cosine_sim)
    output_df.index = similarity_df.index
    output_df.columns = similarity_df.index
    output_df = output_df.to_json()
   
    return print(output_df)
    
    
if __name__=="__main__":
    data = (sys.argv[1])
    compare_time(data)
    




