CREATE TABLE image_gallery(
    image_id VARCHAR(250) NOT NULL,
    username VARCHAR(60) NOT NULL,
    image_name VARCHAR(180) NOT NULL,
    private_image BIT (1) NOT NULL,
    PRIMARY KEY (image_id)
    
  );
