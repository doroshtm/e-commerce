create table categorias(
    id_categorias serial primary key,
    nome text not null,
    descricao text
);

create table produtos(
    id_produto serial primary key,
    nome text not null,
    preco decimal(10,2) not null,
    descricao text,
    imagem varchar(255) not null,
    excluido boolean default false,
    data_exclusao timestamp,
    codigovisual varchar(50) not null,
    custo decimal(10,2) not null,
    margem_lucro decimal(10,2) not null,
    icms decimal(10,2) not null,
    categoria_id integer not null,
    quantidade_estoque integer not null,
    foreign key (categoria_id) references categorias(id_categorias)
);

create table usuarios(
    id_usuario serial primary key,
    nome text not null,
    email text not null,
    senha varchar(255) not null,
    data_cadastro timestamp not null,
    telefone varchar(14) not null,
    cpf varchar(11) not null,
    admin boolean default false not null,
    endereco varchar(255),
    cep varchar(8)
);
