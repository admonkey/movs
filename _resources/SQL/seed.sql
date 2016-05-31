INSERT INTO `Users` VALUES (1,'public','$2y$10$dL0MFPt1HaJ0DDhi6Hp3MuyTGukQUsijUIqw9.woGma13IUqKkNei',NULL),(2,'root','$2y$10$pcHwCUR9DcM/QLSjHH32R.OUAqYlHihv0osq8xmCluZBlG5x4fk.2','?restricted=on&new=on&unbanned=on'),(3,'family','$2y$10$6K49JWvCP8WkgwrALQ1pYe.nix006lvYnqH8gAX94g26FKJxRYCJi','?new=on');

INSERT INTO `Tags` (`Name`) VALUES ('Restricted'),('Watched'),('Starred'),('Banned'),('Action'),('Adventure'),('Animation'),('Biography'),('Comedy'),('Crime'),('Documentary'),('Drama'),('Family'),('Fantasy'),('Film-Noir'),('History'),('Horror'),('Music'),('Musical'),('Mystery'),('Romance'),('Sci-Fi'),('Sport'),('Thriller'),('War'),('Western');

INSERT INTO `Roles` VALUES (1,'admin'),(2,'unrestricted');

INSERT INTO `UserRoles` VALUES (2,1),(1,2);
