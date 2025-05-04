DROP TABLE IF EXISTS [dbo].[_MagOptDesc];

CREATE TABLE [dbo].[_MagOptDesc](
    [id] [int] NOT NULL,
    [name] [nvarchar](255) NOT NULL,
    [desc] [nvarchar](255) NOT NULL,
    [mLevel] [int] NOT NULL,
    [extension] [nvarchar](255) NULL,
    [sortkey] [int] NOT NULL
    ) ON [PRIMARY]
